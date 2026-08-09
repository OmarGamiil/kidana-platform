import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, Fragment, h } from 'vue';
import { Toaster } from '@/components/ui/sonner';
import { initializeTheme } from '@/composables/useAppearance';
import { initializeDirection } from '@/composables/useDirection';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import { i18n } from '@/plugins/i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    setup({ el, App, props, plugin }) {
        // Toaster mounted once at the app root (not inside a specific layout) so
        // every page — marketing, auth/activation, or the sidebar app shell — gets
        // toast feedback regardless of which layout it uses.
        createApp({
            render: () => h(Fragment, [h(App, props), h(Toaster)]),
        })
            .use(plugin)
            .use(i18n)
            .mount(el!);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will set the text direction (rtl/ltr) based on the active locale...
initializeDirection();

// This will listen for flash toast data from the server...
initializeFlashToast();
