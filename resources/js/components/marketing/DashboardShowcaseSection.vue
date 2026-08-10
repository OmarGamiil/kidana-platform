<script setup lang="ts">
import { BellRing, FileCheck2, Smartphone } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import DashboardMarketingPreview from '@/components/marketing/DashboardMarketingPreview.vue';

const { t } = useI18n();

const benefits = [
    { key: 'mobile', icon: Smartphone },
    { key: 'alerts', icon: BellRing },
    { key: 'reports', icon: FileCheck2 },
];
</script>

<template>
    <section class="bg-marketing-cream py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 xl:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-marketing-teal-dark">
                    {{ t('marketing.dashboard.title') }}
                </h2>
                <p class="mt-3 text-sm text-marketing-muted">
                    {{ t('marketing.dashboard.subtitle') }}
                </p>
            </div>
            <div
                class="mt-12 grid items-center gap-12 lg:grid-cols-[1.15fr_0.85fr]"
            >
                <div class="order-2 lg:order-2">
                    <DashboardMarketingPreview />
                </div>
                <div class="order-1 lg:order-1">
                    <span
                        class="inline-flex rounded-full border border-marketing-teal/30 bg-white/60 px-4 py-2 text-xs font-semibold text-marketing-teal"
                        >{{ t('marketing.dashboard.eyebrow') }}</span
                    >
                    <h3
                        class="mt-6 text-3xl leading-snug font-bold text-marketing-teal-dark"
                    >
                        {{ t('marketing.dashboard.headline') }}
                    </h3>
                    <p class="mt-4 text-sm leading-7 text-marketing-muted">
                        {{ t('marketing.dashboard.body') }}
                    </p>
                    <div class="mt-8 grid gap-3">
                        <article
                            v-for="benefit in benefits"
                            :key="benefit.key"
                            class="flex items-start gap-4 rounded-xl border border-marketing-border bg-white p-4"
                        >
                            <span
                                class="flex size-9 shrink-0 items-center justify-center rounded-full bg-marketing-teal text-white"
                                ><component :is="benefit.icon" class="size-4"
                            /></span>
                            <div>
                                <h4
                                    class="text-sm font-bold text-marketing-teal-dark"
                                >
                                    {{
                                        t(
                                            `marketing.dashboard.benefits.${benefit.key}.title`,
                                        )
                                    }}
                                </h4>
                                <p
                                    class="mt-1 text-xs leading-5 text-marketing-muted"
                                >
                                    {{
                                        t(
                                            `marketing.dashboard.benefits.${benefit.key}.body`,
                                        )
                                    }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
