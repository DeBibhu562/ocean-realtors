import { computed, reactive } from 'vue';

export const LEAD_CHATBOT_KEY = Symbol('leadChatbot');
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { chatbotConfig } from '@/config/chatbot';

const HIDDEN_PATH_PREFIXES = [
    '/dashboard',
    '/login',
    '/register',
    '/admin',
    '/profile',
    '/leads',
    '/settings',
    '/forgot-password',
    '/reset-password',
    '/verify-email',
    '/confirm-password',
];

/**
 * Rule-based lead chatbot state. Returns a reactive `chat` object safe to pass as a child prop.
 */
export function useLeadChatbot() {
    const page = usePage();

    const isVisible = computed(() => {
        const path = page.url?.split('?')[0] ?? '';
        return !HIDDEN_PATH_PREFIXES.some((p) => path === p || path.startsWith(p + '/'));
    });

    const propertyContext = computed(() => {
        const prop = page.props?.property;
        if (prop?.id) {
            return { id: prop.id, title: prop.title ?? '' };
        }
        return null;
    });

    const chat = reactive({
        phase: 'idle',
        showTeaser: false,
        teaserIndex: 0,
        intent: '',
        form: {
            name: '',
            dialCode: '+91',
            phoneLocal: '',
            email: '',
            city: 'Gurgaon',
            note: '',
        },
        errors: {},
        submitting: false,
        submitError: '',
    });

    let teaserTimer = null;
    let rotateTimer = null;

    const isDismissed = () => {
        try {
            const until = localStorage.getItem(chatbotConfig.dismissStorageKey);
            if (!until) return false;
            return Date.now() < parseInt(until, 10);
        } catch {
            return false;
        }
    };

    const dismissTeaser = (hours = 24) => {
        try {
            localStorage.setItem(
                chatbotConfig.dismissStorageKey,
                String(Date.now() + hours * 60 * 60 * 1000),
            );
        } catch {
            /* ignore */
        }
        chat.showTeaser = false;
        if (chat.phase === 'teaser') {
            chat.phase = 'idle';
        }
    };

    const stopTimers = () => {
        if (teaserTimer) {
            clearTimeout(teaserTimer);
            teaserTimer = null;
        }
        if (rotateTimer) {
            clearInterval(rotateTimer);
            rotateTimer = null;
        }
    };

    const slideCount = () => chatbotConfig.teaserSlides?.length || 1;

    const revealTeaser = () => {
        if (!isVisible.value || isDismissed()) {
            return;
        }
        if (['open', 'contact', 'preference', 'success'].includes(chat.phase)) {
            return;
        }
        chat.showTeaser = true;
        chat.phase = 'teaser';

        if (!rotateTimer) {
            rotateTimer = setInterval(() => {
                if (chat.showTeaser && !['open', 'contact', 'preference', 'success'].includes(chat.phase)) {
                    chat.teaserIndex = (chat.teaserIndex + 1) % slideCount();
                }
            }, chatbotConfig.teaserRotateMs);
        }
    };

    const startTeaser = () => {
        stopTimers();
        if (!isVisible.value || isDismissed()) {
            return;
        }
        if (['open', 'contact', 'preference', 'success'].includes(chat.phase)) {
            return;
        }

        teaserTimer = setTimeout(() => {
            if (chat.phase === 'idle') {
                revealTeaser();
            }
        }, chatbotConfig.teaserDelayMs);
    };

    const scheduleTeaserAfterClose = () => {
        if (isDismissed()) {
            return;
        }
        teaserTimer = setTimeout(() => {
            if (chat.phase === 'idle') {
                revealTeaser();
            }
        }, chatbotConfig.teaserReopenDelayMs ?? 8000);
    };

    const openPanel = () => {
        stopTimers();
        chat.showTeaser = false;
        chat.phase = 'open';
    };

    const closePanel = () => {
        if (chat.phase !== 'success') {
            chat.phase = 'idle';
        }
        chat.showTeaser = false;
        if (chat.phase !== 'success') {
            scheduleTeaserAfterClose();
        }
    };

    const selectIntent = (intentId) => {
        chat.intent = intentId;
        chat.phase = 'contact';
    };

    const goToPreference = () => {
        chat.errors = {};
        if (!chat.form.name.trim()) {
            chat.errors = { name: 'Please enter your name' };
            return;
        }
        const digits = chat.form.phoneLocal.replace(/\D/g, '');
        if (digits.length < 6) {
            chat.errors = { phoneLocal: 'Enter a valid phone number' };
            return;
        }
        if (chat.form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(chat.form.email.trim())) {
            chat.errors = { email: 'Enter a valid email' };
            return;
        }
        chat.phase = 'preference';
    };

    const fullPhone = () => `${chat.form.dialCode}${chat.form.phoneLocal.replace(/\D/g, '')}`;

    const submitLead = async () => {
        chat.submitError = '';
        chat.submitting = true;
        try {
            const noteParts = [];
            if (chat.form.note.trim()) {
                noteParts.push(chat.form.note.trim());
            }
            if (propertyContext.value?.title) {
                noteParts.push(`Page: ${propertyContext.value.title}`);
            }

            await axios.post('/api/leads', {
                property_id: propertyContext.value?.id ?? null,
                name: chat.form.name.trim(),
                phone: fullPhone(),
                email: chat.form.email.trim() || null,
                intent: chat.intent || null,
                city: chat.form.city,
                message: noteParts.length ? noteParts.join('\n') : null,
                source: 'chatbot',
            });

            chat.phase = 'success';
        } catch (e) {
            chat.submitError =
                e.response?.data?.message ||
                (e.response?.status === 422
                    ? Object.values(e.response.data.errors || {})
                          .flat()
                          .join(' ')
                    : 'Something went wrong. Please try again.');
        } finally {
            chat.submitting = false;
        }
    };

    const currentTeaserSlide = computed(() => {
        const slides = chatbotConfig.teaserSlides ?? [];
        return slides[chat.teaserIndex] ?? slides[0] ?? { headline: '', message: '', cta: 'Start chat' };
    });

    const intentReply = computed(() => chatbotConfig.intentReplies[chat.intent] ?? '');

    const firstName = computed(() => {
        const n = chat.form.name.trim().split(' ')[0];
        return n || 'there';
    });

    const isPanelOpen = computed(() =>
        ['open', 'contact', 'preference', 'success'].includes(chat.phase),
    );

    return {
        chatbotConfig,
        isVisible,
        propertyContext,
        chat,
        currentTeaserSlide,
        intentReply,
        firstName,
        isPanelOpen,
        startTeaser,
        stopTimers,
        openPanel,
        closePanel,
        dismissTeaser,
        selectIntent,
        goToPreference,
        submitLead,
    };
}
