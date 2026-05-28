import { computed } from 'vue';
import {
    buildPropertyEnquiryWhatsAppMessage,
    formatPhoneDisplay,
    telHref,
    whatsAppHref,
} from '@/utils/propertyEnquiry';

/**
 * @param {import('vue').Ref<object>|import('vue').ComputedRef<object>} agentRef
 * @param {import('vue').Ref<object>|import('vue').ComputedRef<object>} propertyRef
 */
export function usePropertyContact(agentRef, propertyRef) {
    const agentPhone = computed(
        () => agentRef.value?.phone || agentRef.value?.whatsapp_phone || '',
    );

    const agentWhatsAppPhone = computed(
        () => agentRef.value?.whatsapp_phone || agentRef.value?.phone || '',
    );

    const phoneDisplay = computed(() => formatPhoneDisplay(agentPhone.value));

    const listingUrl = computed(() => {
        const fromSeo = propertyRef.value?.seo?.canonical;
        if (fromSeo) return fromSeo;
        if (typeof window !== 'undefined' && propertyRef.value?.slug) {
            return `${window.location.origin}/${propertyRef.value.slug}`;
        }
        return typeof window !== 'undefined' ? window.location.href : '';
    });

    const whatsappMessage = computed(() => {
        if (!propertyRef.value) {
            return `Hi ${agentRef.value?.name || 'Ocean Realtors'}, I would like to enquire about a property on Ocean Realtors.`;
        }
        return buildPropertyEnquiryWhatsAppMessage(
            agentRef.value?.name || 'Ocean Realtors',
            propertyRef.value,
            listingUrl.value,
        );
    });

    const waLink = computed(() => whatsAppHref(agentWhatsAppPhone.value, whatsappMessage.value));

    const callLink = computed(() => telHref(agentPhone.value));

    const hasContact = computed(() => !!agentPhone.value || !!agentWhatsAppPhone.value);

    const contactStorageKey = computed(() =>
        propertyRef.value?.id ? `ocean_contact_ok_${propertyRef.value.id}` : null,
    );

    const hasSavedContact = () => {
        if (!contactStorageKey.value) return false;
        try {
            return sessionStorage.getItem(contactStorageKey.value) === '1';
        } catch {
            return false;
        }
    };

    const openChannel = (channel, { onNeedDetails }) => {
        if (!hasContact.value) return;
        if (hasSavedContact()) {
            if (channel === 'call') {
                window.location.href = callLink.value;
            } else {
                window.open(waLink.value, '_blank', 'noopener,noreferrer');
            }
            return;
        }
        onNeedDetails?.(channel);
    };

    return {
        agentPhone,
        phoneDisplay,
        waLink,
        callLink,
        whatsappMessage,
        listingUrl,
        hasContact,
        openChannel,
    };
}
