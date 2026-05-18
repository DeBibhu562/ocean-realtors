import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: { DEFAULT: '#1a56db', hover: '#1e429f', light: '#ebf5ff' },
                accent: { DEFAULT: '#0e9f6e' }, // Green for Sale
                navy: '#0f172a',
                surface: '#f9fafb',
                border: '#e5e7eb',
                'text-primary': '#111827',
                'text-secondary': '#6b7280',
                'text-muted': '#9ca3af',
                'star': '#fbbf24',
            },
            fontFamily: { sans: ['Inter', ...defaultTheme.fontFamily.sans] },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.5rem', { lineHeight: '2rem' }],
                '2xl': ['2rem', { lineHeight: '2.5rem' }],
                '3xl': ['2.5rem', { lineHeight: '3rem' }],
            },
            borderRadius: { 'md': '0.375rem', 'lg': '0.5rem', 'xl': '0.75rem', '2xl': '1rem' },
            boxShadow: {
                'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            }
        },
    },
    plugins: [
        '@tailwindcss/forms',
        '@tailwindcss/typography',
    ],
};
