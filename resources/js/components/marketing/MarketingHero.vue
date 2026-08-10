<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CheckCircle2 } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import HeroDashboardPreview from '@/components/marketing/HeroDashboardPreview.vue';
import { access } from '@/routes';

const { t } = useI18n();
</script>

<template>
    <section class="overflow-hidden bg-marketing-cream">
        <div
            class="mx-auto grid max-w-7xl items-center gap-14 px-6 py-16 lg:grid-cols-[0.94fr_1.06fr] lg:py-[5.5rem] xl:gap-16 xl:px-8"
        >
            <div class="order-2">
                <HeroDashboardPreview />
            </div>

            <div class="order-1 text-start">
                <div
                    class="inline-flex min-h-9 items-center rounded-full border border-marketing-teal/35 bg-white/45 px-5 text-sm font-medium text-marketing-teal"
                >
                    {{ t('marketing.hero.eyebrow') }}
                </div>
                <h1
                    class="mt-7 text-[clamp(2.5rem,4vw,3.75rem)] leading-[1.16] font-bold tracking-tight text-marketing-teal-dark"
                >
                    <span class="block">{{
                        t('marketing.hero.title_line_one')
                    }}</span>
                    <span class="block text-marketing-gold">{{
                        t('marketing.hero.title_line_two')
                    }}</span>
                </h1>
                <p
                    class="mt-6 max-w-xl text-base leading-8 text-marketing-muted lg:text-lg"
                >
                    {{ t('marketing.hero.subtitle') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <Link
                        :href="access()"
                        class="inline-flex h-12 items-center justify-center rounded-lg bg-marketing-gold px-6 text-sm font-bold text-marketing-teal-dark transition-colors hover:bg-marketing-gold/85 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-gold"
                    >
                        {{ t('marketing.hero.cta_primary') }}
                    </Link>
                    <Link
                        :href="access()"
                        class="inline-flex h-12 items-center justify-center rounded-lg border border-marketing-teal bg-white/70 px-6 text-sm font-semibold text-marketing-teal transition-colors hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-teal"
                    >
                        {{ t('marketing.hero.cta_secondary') }}
                    </Link>
                </div>
                <div
                    class="mt-6 flex items-center gap-2 text-sm text-marketing-muted"
                >
                    <CheckCircle2 class="size-4 shrink-0 text-marketing-teal" />
                    <span>{{ t('marketing.hero.supporting_line') }}</span>
                </div>
            </div>
        </div>
    </section>
</template>
