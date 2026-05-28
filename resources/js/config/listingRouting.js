/**
 * Canonical routing: category (Residential | Commercial) × listing_type (Rent | Sell | PG).
 * Property types depend on both; PG mode uses dedicated PG property types.
 */

export const CATEGORIES = ['Residential', 'Commercial'];

export const LISTING_TYPES = ['Rent', 'Sell', 'PG'];

export const USER_ROLES = ['Owner', 'Broker', 'Builder'];

export const LISTING_STATUSES = ['Draft', 'Pending_Review', 'Active', 'Rejected', 'Closed'];

/** @type {Record<string, Record<string, string[]>>} */
const PROPERTY_TYPES_BY_CATEGORY_AND_LISTING = {
    Residential: {
        Rent: [
            'Apartment',
            'Independent House',
            'Villa',
            'Builder Floor',
            'Studio',
            'Penthouse',
            'Plot',
        ],
        Sell: [
            'Apartment',
            'Independent House',
            'Villa',
            'Builder Floor',
            'Studio',
            'Penthouse',
            'Plot',
        ],
        PG: [
            'Co-living Space',
            'Student Hostel',
            'Working Professionals PG',
            'Room in Apartment',
        ],
    },
    Commercial: {
        Rent: [
            'Office Space',
            'Retail Shop',
            'Warehouse',
            'Showroom',
            'Commercial Land',
            'Co-working',
        ],
        Sell: [
            'Office Space',
            'Retail Shop',
            'Warehouse',
            'Showroom',
            'Commercial Land',
            'Industrial Shed',
        ],
        PG: [],
    },
};

/**
 * @param {string} category
 * @param {string} listingType
 * @returns {string[]}
 */
export function getPropertyTypesFor(category, listingType) {
    const cat = PROPERTY_TYPES_BY_CATEGORY_AND_LISTING[category];
    if (!cat) return [];
    const list = cat[listingType];
    if (list === undefined) return [];
    return [...list];
}

/**
 * @param {string} category
 * @param {string} listingType
 * @returns {boolean}
 */
export function isListingCombinationAllowed(category, listingType) {
    if (listingType === 'PG' && category === 'Commercial') {
        return false;
    }
    return CATEGORIES.includes(category) && LISTING_TYPES.includes(listingType);
}

/**
 * @param {string} listingType
 * @returns {string}
 */
export function defaultStatusLabelForListingType(listingType) {
    if (listingType === 'Sell') return 'For Sale';
    if (listingType === 'Rent') return 'For Rent';
    if (listingType === 'PG') return 'PG';
    return 'For Rent';
}

/**
 * If current type is not in the resolved list, return first allowed type.
 * @param {string} category
 * @param {string} listingType
 * @param {string} currentType
 * @returns {string}
 */
export function coercePropertyType(category, listingType, currentType) {
    const allowed = getPropertyTypesFor(category, listingType);
    if (allowed.length === 0) return '';
    if (currentType && allowed.includes(currentType)) return currentType;
    return allowed[0];
}

/**
 * Canonical public URL for a property detail page.
 * @param {string|number|null|undefined} slugOrId
 * @returns {string}
 */
export function propertyShowPath(slugOrId) {
    if (!slugOrId) {
        return '/properties';
    }
    const segment = String(slugOrId).replace(/^\/+/, '');
    return `/properties/${segment}`;
}
