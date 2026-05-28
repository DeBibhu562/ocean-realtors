export const siteLogo = {
    src: '/images/property-placeholder.svg',
    srcWebp: '/images/property-placeholder.svg',
    srcWebp180: '/images/property-placeholder.svg',
    srcWebp220: '/images/property-placeholder.svg',
    srcAlt: '/images/property-placeholder.svg',
    mark: '/images/property-placeholder.svg',
    alt: 'Ocean Realtors',
};

export const siteContact = {
    company: 'Ocean Realtors',
    address: {
        line1: 'SCO M26, Second Floor',
        line2: 'Sector 14, Gurgaon',
        state: 'Haryana 122001',
        full: 'SCO M26, Second Floor, Sector 14 Gurgaon, Haryana 122001',
    },
    phone: '9990633797',
    phoneDisplay: '+91 99906 33797',
    phoneTel: '+919990633797',
    whatsapp: '919990633797',
    email: 'hello@oceanrealtors.co.in',
    careersEmail: 'careers@oceanrealtors.co.in',
    privacyEmail: 'privacy@oceanrealtors.co.in',
    hours: 'Mon – Sat, 10:00 AM – 7:00 PM',
    mapEmbedUrl: 'https://maps.google.com/maps?q=SCO+M26+Sector+14+Gurugram+Haryana+122001&t=&z=16&ie=UTF8&iwloc=&output=embed',
};

export function whatsappUrl(message = 'Hi Ocean Realtors, I would like to enquire about a property.') {
    return `https://wa.me/${siteContact.whatsapp}?text=${encodeURIComponent(message)}`;
}

export const footerLinks = {
    company: [
        { label: 'Blog', href: '/blog' },
        { label: 'About Us', href: '/about' },
        { label: 'Mission & Vision', href: '/mission' },
        { label: 'Why Choose Us', href: '/why-choose-us' },
        { label: 'Testimonials', href: '/testimonials' },
        { label: 'Join Our Team', href: '/careers' },
    ],
    legal: [
        { label: 'Privacy Policy', href: '/privacy-policy' },
        { label: 'Terms & Conditions', href: '/terms' },
    ],
    business: [
        { label: 'Contact Us', href: '/contact' },
        { label: 'Business Proposal', href: '/proposal' },
        { label: 'Advertisements', href: '/ads' },
    ],
    explore: [
        { label: 'Browse Properties', href: '/properties' },
        { label: 'Our Agents', href: '/agents' },
        { label: 'Rent in Gurgaon', href: '/properties?city=Gurgaon&status=rent' },
        { label: 'Buy in NCR', href: '/properties?status=sale' },
    ],
};
