import { nextTick, ref, watch } from 'vue';

const SQFT_PER_SQM = 10.7639;
const SQFT_PER_SQYD = 9;

const toSqM = (sqFt) => sqFt / SQFT_PER_SQM;
const toSqYd = (sqFt) => sqFt / SQFT_PER_SQYD;
const toSqFtFromSqM = (sqM) => sqM * SQFT_PER_SQM;
const toSqFtFromSqYd = (sqYd) => sqYd * SQFT_PER_SQYD;

/** Keep only whole digits (integers). */
const sanitizeInteger = (raw) => String(raw ?? '').replace(/\D/g, '');

const toDisplayInt = (value) => {
    const n = Math.round(Number(value));
    if (!Number.isFinite(n) || n <= 0) {
        return '';
    }
    return String(n);
};

const sqFtFromSource = (source, integerValue) => {
    if (source === 'sqft') {
        return integerValue;
    }
    if (source === 'sqm') {
        return Math.round(toSqFtFromSqM(integerValue));
    }
    return Math.round(toSqFtFromSqYd(integerValue));
};

/**
 * Linked sq.ft / sq.m / sq.yd fields — integers only, any field can drive the others.
 *
 * @param {(value: string) => void} [onCanonicalChange] Canonical sq.ft (integer string) for form storage
 */
export function useAreaUnitConverter(onCanonicalChange) {
    const sqFt = ref('');
    const sqM = ref('');
    const sqYd = ref('');
    let syncing = false;

    const clearAll = () => {
        syncing = true;
        sqFt.value = '';
        sqM.value = '';
        sqYd.value = '';
        onCanonicalChange?.('');
        syncing = false;
    };

    const syncFrom = async (source, rawValue) => {
        const digits = sanitizeInteger(rawValue);

        if (digits === '') {
            clearAll();
            return;
        }

        const n = parseInt(digits, 10);
        if (!Number.isFinite(n) || n <= 0) {
            return;
        }

        const canonicalSqFt = sqFtFromSource(source, n);

        syncing = true;

        // Keep the field being edited as the user's digits (no decimals forced on source).
        if (source === 'sqft') {
            sqFt.value = digits;
            sqM.value = toDisplayInt(toSqM(canonicalSqFt));
            sqYd.value = toDisplayInt(toSqYd(canonicalSqFt));
        } else if (source === 'sqm') {
            sqM.value = digits;
            sqFt.value = toDisplayInt(canonicalSqFt);
            sqYd.value = toDisplayInt(toSqYd(canonicalSqFt));
        } else {
            sqYd.value = digits;
            sqFt.value = toDisplayInt(canonicalSqFt);
            sqM.value = toDisplayInt(toSqM(canonicalSqFt));
        }

        onCanonicalChange?.(String(canonicalSqFt));

        await nextTick();
        syncing = false;
    };

    const normalizeAll = async (source) => {
        const raw =
            source === 'sqft' ? sqFt.value : source === 'sqm' ? sqM.value : sqYd.value;
        await syncFrom(source, raw);
    };

    const setFromSqFt = async (stored) => {
        const ft = Math.round(Number(stored));
        if (!Number.isFinite(ft) || ft <= 0) {
            clearAll();
            return;
        }
        await syncFrom('sqft', String(ft));
    };

    const bindField = (source, fieldRef) => {
        watch(fieldRef, (val) => {
            if (syncing) {
                return;
            }
            syncFrom(source, val);
        });
    };

    bindField('sqft', sqFt);
    bindField('sqm', sqM);
    bindField('sqyd', sqYd);

    return {
        sqFt,
        sqM,
        sqYd,
        setFromSqFt,
        clearAll,
        normalizeSqFt: () => normalizeAll('sqft'),
        normalizeSqM: () => normalizeAll('sqm'),
        normalizeSqYd: () => normalizeAll('sqyd'),
    };
}
