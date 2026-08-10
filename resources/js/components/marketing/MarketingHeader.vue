<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LanguageToggle from '@/components/LanguageToggle.vue';
import { access, home } from '@/routes';

const { t } = useI18n();
</script>

<template>
    <header class="border-b border-marketing-border/80 bg-marketing-cream">
        <div
            class="mx-auto flex h-20 max-w-7xl items-center gap-7 px-6 xl:px-8"
        >
            <Link
                :href="home()"
                class="shrink-0 rounded-md text-marketing-teal-dark focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
                :aria-label="t('marketing.nav.home')"
            >
                <img
                    src="/images/kidana-logo.jpg"
                    :alt="t('marketing.brand_alt')"
                    class="h-12 w-auto max-w-36 object-contain mix-blend-multiply"
                />
            </Link>

            <nav
                class="hidden min-w-0 flex-1 items-center gap-5 text-[13px] font-medium text-marketing-teal-dark lg:flex"
                :aria-label="t('marketing.nav.label')"
            >
                <Link
                    :href="home()"
                    class="whitespace-nowrap hover:text-marketing-teal"
                >
                    {{ t('marketing.nav.home') }}
                </Link>
                <span class="whitespace-nowrap">{{
                    t('marketing.nav.what_we_offer')
                }}</span>
                <span class="whitespace-nowrap">{{
                    t('marketing.nav.how_it_works')
                }}</span>
                <a
                    href="#capabilities"
                    class="whitespace-nowrap hover:text-marketing-teal"
                >
                    {{ t('marketing.nav.services') }}
                </a>
                <Link
                    :href="access()"
                    class="whitespace-nowrap hover:text-marketing-teal"
                >
                    {{ t('marketing.nav.readiness') }}
                </Link>
                <span class="whitespace-nowrap">{{
                    t('marketing.nav.knowledge')
                }}</span>
            </nav>

            <div class="ms-auto flex shrink-0 items-center gap-2">
                <LanguageToggle />
                <Link
                    :href="access()"
                    class="hidden h-10 items-center justify-center rounded-lg bg-marketing-teal px-4 text-sm font-semibold text-white transition-colors hover:bg-marketing-teal-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-teal sm:inline-flex"
                >
                    {{ t('marketing.nav.login') }}
                </Link>
                <Link
                    :href="access()"
                    class="hidden h-10 items-center justify-center rounded-lg bg-marketing-gold px-4 text-sm font-semibold text-marketing-teal-dark transition-colors hover:bg-marketing-gold/85 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-gold md:inline-flex"
                >
                    {{ t('marketing.nav.quote') }}
                </Link>
            </div>
        </div>
    </header>
</template>
