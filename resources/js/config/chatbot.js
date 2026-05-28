/** Rule-based lead chatbot copy — edit here without touching components */

export const chatbotConfig = {
    assistantName: 'Ocean Realtors Team',
    assistantTitle: 'Property assistant',
    teaserDelayMs: 2500,
    teaserRotateMs: 5000,
    teaserReopenDelayMs: 8000,
    dismissStorageKey: 'ocean_lead_chatbot_dismissed_until',

    teaserGreeting: 'Hi there! 👋',
    teaserAgentLabel: 'Property expert · Online now',

    teaserSlides: [
        {
            headline: 'Looking for a home in Gurgaon?',
            message: 'Get a free expert callback & verified shortlist within 2 hours.',
            cta: 'Start free chat',
        },
        {
            headline: 'Rent or buy in NCR?',
            message: '500+ verified listings — we match you with the right property.',
            cta: 'Get help now',
        },
        {
            headline: 'Free property consultation',
            message: 'No spam · No obligation · Talk to a real advisor today.',
            cta: 'Chat with us',
        },
        {
            headline: 'Still browsing?',
            message: 'Tell us your budget & area — we will send options on WhatsApp too.',
            cta: 'Open assistant',
        },
    ],

    welcomeLines: [
        'Hi there! Welcome to Ocean Realtors.',
        'I can connect you with a property expert — free, no obligation.',
    ],

    intentOptions: [
        { id: 'rent', label: 'Rent a home', emoji: '🏠' },
        { id: 'buy', label: 'Buy property', emoji: '🔑' },
        { id: 'commercial', label: 'Commercial', emoji: '🏢' },
        { id: 'explore', label: 'Just exploring', emoji: '✨' },
    ],

    intentReplies: {
        rent: 'Great choice! Gurgaon has excellent rental options. Share your details and we will shortlist properties for you.',
        buy: 'Wonderful! Our consultants help buyers across NCR with verified listings and site visits.',
        commercial: 'We handle offices, retail, and commercial spaces across Gurgaon and Delhi NCR.',
        explore: 'No problem — tell us what you have in mind and we will guide you at your pace.',
    },

    cityOptions: ['Gurgaon', 'Delhi', 'Noida', 'Faridabad', 'Other'],

    trustLine: 'No spam · Free consultation · Expert callback',

    success: {
        title: 'You are all set!',
        subtitle: 'Our property expert will call you within 2 hours.',
        browseLabel: 'Browse properties',
        whatsappLabel: 'Chat on WhatsApp',
    },
};
