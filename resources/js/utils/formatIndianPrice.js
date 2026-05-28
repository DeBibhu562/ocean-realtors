/**
 * Format property prices for the Indian market (Cr / L / grouped rupees).
 *
 * @param {number|string|null|undefined} amount
 * @param {{ isRental?: boolean }} [options]
 * @returns {string}
 */
export function formatIndianPrice(amount, { isRental = false } = {}) {
    const value = Number(amount);

    if (!Number.isFinite(value) || value < 0) {
        return '—';
    }

    const rentalSuffix = isRental ? ' / month' : '';

    if (value >= 1_00_00_000) {
        const crores = value / 1_00_00_000;
        const text = formatCompactNumber(crores);

        return `₹ ${text} Cr${rentalSuffix}`;
    }

    if (value >= 1_00_000) {
        const lakhs = value / 1_00_000;
        const text = formatCompactNumber(lakhs);

        return `₹ ${text} Lakh${rentalSuffix}`;
    }

    const grouped = new Intl.NumberFormat('en-IN', {
        maximumFractionDigits: 0,
    }).format(value);

    return `₹ ${grouped}${rentalSuffix}`;
}

/**
 * @param {number} value
 */
function formatCompactNumber(value) {
    if (value >= 100) {
        return String(Math.round(value));
    }

    const rounded = Math.round(value * 10) / 10;

    return Number.isInteger(rounded) ? String(rounded) : rounded.toFixed(1).replace(/\.0$/, '');
}

/**
 * @param {number|string|null|undefined} amount
 * @param {boolean} [isRental]
 */
export function formatIndianPricePlain(amount, isRental = false) {
    return formatIndianPrice(amount, { isRental }).replace(/^₹\s*/, '');
}
