/** Optional numeric fields that must not be sent as empty strings in FormData. */
export const OPTIONAL_NUMERIC_FIELDS = [
    'floor_no',
    'total_floors',
    'built_up_area',
    'carpet_area',
    'workstations',
    'cabins',
    'booking_amount',
    'food_charges',
    'maintenance_charges',
    'latitude',
    'longitude',
    'age_of_property',
    'balconies',
];

/**
 * @param {Record<string, unknown>} form
 */
export function coerceOptionalNumericFields(form) {
    for (const key of OPTIONAL_NUMERIC_FIELDS) {
        const value = form[key];
        if (value === '' || (typeof value === 'string' && value.trim() === '')) {
            form[key] = null;
        }
    }
}
