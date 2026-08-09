import type { ComputedRef, Ref } from 'vue';
import { computed, ref } from 'vue';
import { i18n } from '@/plugins/i18n';
import type { Locale } from '@/plugins/i18n';

export type Direction = 'rtl' | 'ltr';

export type UseDirectionReturn = {
    locale: Ref<Locale>;
    direction: ComputedRef<Direction>;
    setLocale: (value: Locale) => void;
};

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const directionFor = (value: Locale): Direction =>
    value === 'ar' ? 'rtl' : 'ltr';

const applyToDocument = (value: Locale) => {
    if (typeof document === 'undefined') {
        return;
    }

    document.documentElement.lang = value;
    document.documentElement.dir = directionFor(value);
};

const locale = ref<Locale>(i18n.global.locale.value as Locale);

export function initializeDirection(): void {
    applyToDocument(locale.value);
}

export function useDirection(): UseDirectionReturn {
    const direction = computed<Direction>(() => directionFor(locale.value));

    function setLocale(value: Locale) {
        locale.value = value;
        i18n.global.locale.value = value;

        localStorage.setItem('locale', value);
        setCookie('locale', value);
        applyToDocument(value);
    }

    return {
        locale,
        direction,
        setLocale,
    };
}
