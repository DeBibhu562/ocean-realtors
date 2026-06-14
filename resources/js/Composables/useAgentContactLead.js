import { computed, onMounted, ref } from 'vue';
import {
    formatPhoneDisplay,
    telHref,
    whatsAppHref,
} from '@/utils/propertyEnquiry';

const PROFILE_KEY = 'ocean_contact_profile';

function contactOkKey(agentId) {
    return `ocean_contact_ok_agent_${agentId}`;
}

function contactDataKey(agentId) {
    return `ocean_contact_agent_${agentId}`;
}

function loadStoredContact(agentId) {
    if (!agentId) return null;
    try {
        if (sessionStorage.getItem(contactOkKey(agentId)) !== '1') {
            return null;
        }
        const raw = sessionStorage.getItem(contactDataKey(agentId));
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        if (parsed?.phone || parsed?.whatsapp_phone) {
            return parsed;
        }
    } catch {
        /* ignore */
    }
    return null;
}

function storeContact(agentId, contact) {
    if (!agentId || !contact) return;
    try {
        sessionStorage.setItem(contactOkKey(agentId), '1');
        sessionStorage.setItem(contactDataKey(agentId), JSON.stringify(contact));
    } catch {
        /* ignore */
    }
}

/**
 * Lead gate before call / WhatsApp on agent profile pages.
 *
 * @param {import('vue').Ref<object>|import('vue').ComputedRef<object>} agentRef
 */
export function useAgentContactLead(agentRef) {
    const modalOpen = ref(false);
    const modalReady = ref(false);
    const channel = ref('whatsapp');
    const unlockedContact = ref(null);
    const showPhone = ref(false);

    const hasContact = computed(() => agentRef.value?.has_contact !== false);

    const agentPhone = computed(
        () => unlockedContact.value?.phone || unlockedContact.value?.whatsapp_phone || '',
    );

    const agentWhatsAppPhone = computed(
        () => unlockedContact.value?.whatsapp_phone || unlockedContact.value?.phone || '',
    );

    const phoneDisplay = computed(() => formatPhoneDisplay(agentPhone.value));

    const whatsappMessage = computed(
        () => `Hi ${agentRef.value?.name || 'Ocean Realtors'}, I found your profile on Ocean Realtors.`,
    );

    const callLink = computed(() => telHref(agentPhone.value));
    const waLink = computed(() => whatsAppHref(agentWhatsAppPhone.value, whatsappMessage.value));

    const isUnlocked = computed(() => !!unlockedContact.value?.phone || !!unlockedContact.value?.whatsapp_phone);

    const hasSavedContact = () => isUnlocked.value;

    const unlockContact = (contact) => {
        if (!contact) return;
        unlockedContact.value = {
            phone: contact.phone || contact.whatsapp_phone || '',
            whatsapp_phone: contact.whatsapp_phone || contact.phone || '',
        };
        showPhone.value = true;
        if (agentRef.value?.id) {
            storeContact(agentRef.value.id, unlockedContact.value);
        }
    };

    const restoreStoredContact = () => {
        const stored = loadStoredContact(agentRef.value?.id);
        if (stored) {
            unlockedContact.value = stored;
            showPhone.value = true;
        }
    };

    onMounted(() => {
        restoreStoredContact();
        modalReady.value = true;
    });

    const runChannelAction = (nextChannel) => {
        if (nextChannel === 'call' && callLink.value && callLink.value !== '#') {
            window.location.href = callLink.value;
        } else if (nextChannel === 'whatsapp' && waLink.value && waLink.value !== '#') {
            window.open(waLink.value, '_blank', 'noopener,noreferrer');
        }
    };

    const requestContact = (nextChannel, { onViewPhone } = {}) => {
        if (!hasContact.value) {
            return;
        }
        if (hasSavedContact()) {
            runChannelAction(nextChannel);
            if (nextChannel === 'view_phone') {
                showPhone.value = true;
                onViewPhone?.();
            }
            return;
        }
        channel.value = nextChannel;
        modalOpen.value = true;
    };

    const closeModal = () => {
        modalOpen.value = false;
    };

    const handleLeadSuccess = ({ channel: completedChannel, contact }, { onViewPhone } = {}) => {
        if (contact) {
            unlockContact(contact);
        }
        runChannelAction(completedChannel);
        if (completedChannel === 'view_phone') {
            showPhone.value = true;
            onViewPhone?.();
        }
        modalOpen.value = false;
    };

    return {
        modalOpen,
        modalReady,
        channel,
        showPhone,
        isUnlocked,
        phoneDisplay,
        hasContact,
        hasSavedContact,
        callLink,
        waLink,
        requestContact,
        closeModal,
        handleLeadSuccess,
        unlockContact,
    };
}
