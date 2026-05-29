const BLOCK_REGEX = /[\s\S]*?<\/(?:p|h[1-6]|ul|ol|blockquote)>/gi;

const countWords = (html) => {
    const text = html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();

    return text ? text.split(' ').length : 0;
};

/**
 * How many inline CTAs to show based on article length (2–3 for typical posts).
 */
export function calculateCtaCount(totalWords) {
    if (totalWords < 250) {
        return 0;
    }
    if (totalWords < 550) {
        return 2;
    }
    if (totalWords < 900) {
        return 2;
    }

    return 3;
}

/**
 * Word-count thresholds where each CTA should appear (evenly spaced).
 *
 * @param {number} totalWords
 * @param {number} ctaCount
 * @returns {number[]}
 */
export function ctaWordThresholds(totalWords, ctaCount) {
    if (ctaCount <= 0) {
        return [];
    }

    return Array.from({ length: ctaCount }, (_, i) =>
        Math.floor((totalWords / (ctaCount + 1)) * (i + 1)),
    );
}

/**
 * Split article HTML and insert 2–3 inline CTAs at even intervals (~every 300–400 words).
 *
 * @param {string} html
 * @returns {{ type: 'html' | 'cta', content?: string, variant?: number }[]}
 */
export function splitBlogContentForCta(html) {
    const cleaned = (html || '').trim();
    if (!cleaned) {
        return [{ type: 'html', content: '' }];
    }

    const blocks = [];
    let match;
    let lastIndex = 0;

    while ((match = BLOCK_REGEX.exec(cleaned)) !== null) {
        blocks.push(match[0]);
        lastIndex = BLOCK_REGEX.lastIndex;
    }

    const remainder = cleaned.slice(lastIndex).trim();
    if (remainder) {
        blocks.push(remainder);
    }

    if (!blocks.length) {
        blocks.push(cleaned);
    }

    const totalWords = blocks.reduce((sum, block) => sum + countWords(block), 0);
    const ctaCount = calculateCtaCount(totalWords);

    if (ctaCount === 0) {
        return [{ type: 'html', content: cleaned }];
    }

    const thresholds = ctaWordThresholds(totalWords, ctaCount);
    let accumulated = 0;
    let nextCtaIndex = 0;
    const segments = [];
    let htmlBuffer = '';

    for (const block of blocks) {
        const words = countWords(block);
        htmlBuffer += block;
        accumulated += words;

        while (nextCtaIndex < thresholds.length && accumulated >= thresholds[nextCtaIndex]) {
            segments.push({ type: 'html', content: htmlBuffer });
            segments.push({ type: 'cta', variant: (nextCtaIndex + 1) % 3 });
            htmlBuffer = '';
            nextCtaIndex += 1;
        }
    }

    if (htmlBuffer) {
        segments.push({ type: 'html', content: htmlBuffer });
    }

    return segments.length ? segments : [{ type: 'html', content: cleaned }];
}
