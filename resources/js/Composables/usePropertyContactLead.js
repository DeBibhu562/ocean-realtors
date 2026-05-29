import { ref } from 'vue';
import { usePropertyContact } from '@/Composables/usePropertyContact';

/**
 * Lead gate before call / WhatsApp / view number on listing cards and detail pages.
 *
 * @param {import('vue').Ref<object>|import('vue').ComputedRef<object>} agentRef
 * @param {import('vue').Ref<object>|import('vue').ComputedRef<object>} propertyRef
 */
export function usePropertyContactLead(agentRef, propertyRef) {
    const modalOpen = ref(false);
    const modalReady = ref(false);
    const channel = ref('whatsapp');

    const contact = usePropertyContact(agentRef, propertyRef);

    const requestContact = (nextChannel, { onViewPhone } = {}) => {
        if (!contact.hasContact.value) {
            return;
        }
        if (contact.hasSavedContact()) {
            runChannelAction(nextChannel);
            if (nextChannel === 'view_phone') {
                onViewPhone?.();
            }
            return;
        }
        channel.value = nextChannel;
        modalReady.value = true;
        modalOpen.value = true;
    };

    const closeModal = () => {
        modalOpen.value = false;
    };

    const runChannelAction = (nextChannel) => {
        if (nextChannel === 'call' && contact.callLink.value) {
            window.location.href = contact.callLink.value;
        } else if (nextChannel === 'whatsapp' && contact.waLink.value) {
            window.open(contact.waLink.value, '_blank', 'noopener,noreferrer');
        }
    };

    const handleLeadSuccess = ({ channel: completedChannel }, { onViewPhone } = {}) => {
        runChannelAction(completedChannel);
        if (completedChannel === 'view_phone') {
            onViewPhone?.();
        }
        modalOpen.value = false;
    };

    return {
        modalOpen,
        modalReady,
        channel,
        requestContact,
        closeModal,
        handleLeadSuccess,
        ...contact,
    };
}
