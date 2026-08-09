<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLogo from '@/components/AppLogo.vue';
import LanguageToggle from '@/components/LanguageToggle.vue';
import { access, home } from '@/routes';

const { t } = useI18n();

const audienceLinks = [
    { label: t('marketing.footer.audiences.office'), href: access() },
    { label: t('marketing.footer.audiences.service_provider'), href: access() },
    { label: t('marketing.footer.audiences.kidana_team'), href: access() },
    { label: t('marketing.footer.audiences.ministry'), href: access() },
];
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <header
            class="sticky top-0 z-20 border-b border-border bg-background/95 backdrop-blur"
        >
            <div
                class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6"
            >
                <Link :href="home()" class="flex items-center gap-2">
                    <AppLogo />
                </Link>
                <nav class="flex items-center gap-2">
                    <LanguageToggle />
                    <Link
                        :href="access()"
                        class="inline-flex h-9 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                    >
                        {{ t('marketing.nav.login') }}
                    </Link>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="border-t border-border bg-muted/30">
            <div
                class="mx-auto grid max-w-6xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-4"
            >
                <div class="md:col-span-1">
                    <AppLogo />
                    <p class="mt-3 text-sm text-muted-foreground">
                        {{ t('marketing.footer.tagline') }}
                    </p>
                </div>
                <div
                    v-for="link in audienceLinks"
                    :key="link.label"
                    class="text-sm"
                >
                    <Link
                        :href="link.href"
                        class="text-muted-foreground hover:text-foreground"
                    >
                        {{ link.label }}
                    </Link>
                </div>
            </div>
            <div
                class="border-t border-border py-4 text-center text-xs text-muted-foreground"
            >
                {{ t('marketing.footer.copyright') }}
            </div>
        </footer>
    </div>
</template>
