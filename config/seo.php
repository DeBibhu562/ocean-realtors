<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Static pages for sitemap & default SEO
    |--------------------------------------------------------------------------
    */
    'static_pages' => [
        ['path' => '/', 'priority' => 1.0, 'changefreq' => 'daily', 'title' => 'Home', 'description' => 'Ocean Realtors — premium property listings for rent and sale in Gurgaon, Delhi NCR. Verified homes, villas, and commercial spaces with dedicated agents.'],
        ['path' => '/properties', 'priority' => 0.9, 'changefreq' => 'hourly', 'title' => 'Search Properties', 'description' => 'Browse verified apartments, villas, and commercial properties for rent and sale across Gurgaon and NCR.'],
        ['path' => '/agents', 'priority' => 0.8, 'changefreq' => 'weekly', 'title' => 'Our Agents', 'description' => 'Meet Ocean Realtors property consultants — call or WhatsApp your dedicated agent for viewings and expert advice.'],
        ['path' => '/blog', 'priority' => 0.8, 'changefreq' => 'weekly', 'title' => 'Blog', 'description' => 'Real estate insights, locality guides, and home-buying tips from Ocean Realtors.'],
        ['path' => '/about', 'priority' => 0.7, 'changefreq' => 'monthly', 'title' => 'About Us', 'description' => 'Learn about Ocean Realtors — trusted property consultants in Gurgaon and Delhi NCR since day one.'],
        ['path' => '/contact', 'priority' => 0.7, 'changefreq' => 'monthly', 'title' => 'Contact Us', 'description' => 'Contact Ocean Realtors in Sector 14, Gurgaon. Call, WhatsApp, or visit our office for property enquiries.'],
        ['path' => '/mission', 'priority' => 0.6, 'changefreq' => 'yearly', 'title' => 'Mission & Vision', 'description' => 'Our mission to deliver transparent, client-first real estate services across the National Capital Region.'],
        ['path' => '/why-choose-us', 'priority' => 0.6, 'changefreq' => 'yearly', 'title' => 'Why Choose Us', 'description' => 'Why home seekers and owners trust Ocean Realtors for verified listings and dedicated agents.'],
        ['path' => '/testimonials', 'priority' => 0.6, 'changefreq' => 'weekly', 'title' => 'Testimonials', 'description' => 'Read reviews from renters, buyers, and property owners who worked with Ocean Realtors.'],
        ['path' => '/careers', 'priority' => 0.5, 'changefreq' => 'monthly', 'title' => 'Careers', 'description' => 'Join the Ocean Realtors team — careers in real estate sales and consulting in Gurgaon.'],
        ['path' => '/proposal', 'priority' => 0.5, 'changefreq' => 'yearly', 'title' => 'Business Proposal', 'description' => 'Partner with Ocean Realtors — business proposals for developers, brokers, and corporate clients.'],
        ['path' => '/ads', 'priority' => 0.5, 'changefreq' => 'yearly', 'title' => 'Advertisements', 'description' => 'Advertise your property or brand on Ocean Realtors — reach qualified NCR audiences.'],
        ['path' => '/privacy-policy', 'priority' => 0.4, 'changefreq' => 'yearly', 'title' => 'Privacy Policy', 'description' => 'Ocean Realtors privacy policy — how we collect, use, and protect your personal information.'],
        ['path' => '/terms', 'priority' => 0.4, 'changefreq' => 'yearly', 'title' => 'Terms & Conditions', 'description' => 'Terms and conditions for using the Ocean Realtors website and services.'],
    ],

    'robots_disallow' => [
        '/dashboard',
        '/profile',
        '/api',
        '/admin',
        '/leads',
        '/settings',
        '/login',
        '/register',
        '/forgot-password',
        '/reset-password',
        '/verify-email',
        '/confirm-password',
    ],

];
