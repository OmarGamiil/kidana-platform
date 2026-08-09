import { createI18n } from 'vue-i18n';
import ar from '@/lang/ar.json';
import en from '@/lang/en.json';

export type Locale = 'ar' | 'en';

const getInitialLocale = (): Locale => {
    if (typeof document === 'undefined') {
        return 'ar';
    }

    const htmlLocale = document.documentElement.lang?.split('-')[0];

    return htmlLocale === 'en' ? 'en' : 'ar';
};

export const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: getInitialLocale(),
    fallbackLocale: 'en',
    messages: { ar, en },
});
