import { computed } from 'vue';

/**
 * Build meta + JSON-LD for a blog post page.
 */
export function useBlogSeo(post) {
    const seo = computed(() => post?.seo ?? {});

    const pageTitle = computed(() => {
        const t = seo.value.title || post?.title || 'Blog';
        return t.includes('Ocean Realtors') ? t : `${t} | Ocean Realtors`;
    });

    const description = computed(() => seo.value.description || post?.excerpt || '');

    const ogImage = computed(() => {
        const img = seo.value.og_image || post?.featured_image;
        if (!img) return null;
        if (img.startsWith('http')) return img;
        return `${window.location.origin}${img.startsWith('/') ? img : '/'+img}`;
    });

    const canonical = computed(() => {
        if (seo.value.canonical) {
            return seo.value.canonical;
        }
        const slug = (post?.slug || '').replace(/^\/+/, '');

        return `${window.location.origin}/blog/${slug}`;
    });

    const robots = computed(() => (seo.value.noindex ? 'noindex, nofollow' : 'index, follow'));

    const jsonLd = computed(() => {
        if (!post?.title) return null;

        return JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'BlogPosting',
            headline: post.title,
            description: description.value,
            image: ogImage.value ? [ogImage.value] : undefined,
            datePublished: post.published_at,
            author: post.author?.name
                ? {
                    '@type': 'Person',
                    name: post.author.name,
                    ...(post.author.photo
                        ? {
                            image: post.author.photo.startsWith('http')
                                ? post.author.photo
                                : `${typeof window !== 'undefined' ? window.location.origin : ''}${post.author.photo}`,
                        }
                        : {}),
                }
                : {
                    '@type': 'Organization',
                    name: 'Ocean Realtors',
                },
            publisher: {
                '@type': 'Organization',
                name: 'Ocean Realtors',
            },
            mainEntityOfPage: canonical.value,
        });
    });

    return {
        pageTitle,
        description,
        ogImage,
        canonical,
        robots,
        jsonLd,
    };
}
