<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpLeft } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import LanguageToggle from '@/components/LanguageToggle.vue';
import { home } from '@/routes';

const { t } = useI18n();
</script>

<template>
    <header class="bg-marketing-cream">
        <div
            class="mx-auto flex h-[4.5rem] max-w-7xl items-center justify-between gap-6 px-6 xl:px-8"
        >
            <Link
                :href="home()"
                class="rounded-md focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
            >
                <img
                    src="/images/kidana-logo.jpg"
                    :alt="t('marketing.brand_alt')"
                    class="h-9 w-auto max-w-32 object-contain mix-blend-multiply"
                />
            </Link>
            <div class="flex items-center gap-3">
                <Link
                    :href="home()"
                    class="inline-flex h-9 items-center gap-2 rounded-lg px-3 text-sm font-semibold text-marketing-teal-dark transition-colors hover:bg-white/55 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-teal"
                >
                    <ArrowUpLeft
                        class="size-4 rtl:-scale-x-100"
                        aria-hidden="true"
                    />
                    {{ t('common.back_to_website') }}
                </Link>
                <div
                    class="rounded-lg border border-marketing-teal/30 bg-white/45 text-marketing-teal-dark [&_button]:h-9 [&_button]:px-3 [&_button]:text-marketing-teal-dark"
                >
                    <LanguageToggle />
                </div>
            </div>
        </div>
    </header>
</template>
