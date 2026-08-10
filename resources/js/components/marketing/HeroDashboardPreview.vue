<script setup lang="ts">
import { Check, FileCheck2, MapPin, ShieldCheck } from '@lucide/vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const metrics = [
    { value: '11/13', key: 'stations', icon: MapPin },
    { value: '4', key: 'tracks', icon: ShieldCheck },
    { value: '18', key: 'reports', icon: FileCheck2 },
];
</script>

<template>
    <div class="relative isolate mx-auto w-full max-w-[570px]">
        <div
            class="absolute -inset-5 -z-10 rounded-[2.25rem] bg-[radial-gradient(circle_at_15%_88%,var(--marketing-gold-soft),transparent_47%)] opacity-80"
        ></div>
        <div
            class="overflow-hidden rounded-[1.75rem] bg-marketing-teal-dark p-5 shadow-xl shadow-marketing-teal-dark/15 sm:p-6"
        >
            <div class="mb-5 flex items-center justify-between text-white">
                <div>
                    <p class="text-xs text-white/65">
                        {{ t('marketing.preview.season') }}
                    </p>
                    <p class="mt-1 text-base font-semibold">
                        {{ t('marketing.preview.title') }}
                    </p>
                </div>
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs"
                >
                    <span class="size-2 rounded-full bg-marketing-gold"></span>
                    {{ t('marketing.preview.status') }}
                </span>
            </div>

            <div
                class="rounded-2xl bg-white p-5 text-marketing-teal-dark sm:p-6"
            >
                <div class="flex items-start justify-between gap-5">
                    <div>
                        <p class="text-sm font-semibold">
                            {{ t('marketing.preview.readiness') }}
                        </p>
                        <p class="mt-1 text-xs text-marketing-muted">
                            {{ t('marketing.preview.updated') }}
                        </p>
                    </div>
                    <div class="text-end">
                        <strong class="text-4xl leading-none font-bold"
                            >80.7%</strong
                        >
                        <p class="mt-1 text-xs text-marketing-teal">
                            {{ t('marketing.preview.on_track') }}
                        </p>
                    </div>
                </div>

                <div
                    class="mt-5 h-2.5 overflow-hidden rounded-full bg-marketing-cream"
                >
                    <div
                        class="h-full w-[80.7%] rounded-full bg-marketing-gold"
                    ></div>
                </div>

                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div
                        v-for="metric in metrics"
                        :key="metric.key"
                        class="rounded-xl border border-marketing-border bg-marketing-surface p-3"
                    >
                        <component
                            :is="metric.icon"
                            class="size-4 text-marketing-teal"
                        />
                        <strong class="mt-3 block text-lg">{{
                            metric.value
                        }}</strong>
                        <span
                            class="mt-0.5 block text-[11px] text-marketing-muted"
                            >{{
                                t(`marketing.preview.metrics.${metric.key}`)
                            }}</span
                        >
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-marketing-teal p-4 text-white">
                    <p class="text-xs text-white/65">
                        {{ t('marketing.preview.next_step') }}
                    </p>
                    <p class="mt-2 text-sm font-semibold">
                        {{ t('marketing.preview.field_review') }}
                    </p>
                    <div
                        class="mt-4 flex items-center gap-2 text-xs text-marketing-gold-soft"
                    >
                        <Check class="size-4" />
                        {{ t('marketing.preview.scheduled') }}
                    </div>
                </div>
                <div
                    class="rounded-2xl bg-marketing-gold p-4 text-marketing-teal-dark"
                >
                    <p class="text-xs opacity-70">
                        {{ t('marketing.preview.compliance') }}
                    </p>
                    <p class="mt-2 text-2xl font-bold">92%</p>
                    <p class="mt-3 text-xs">
                        {{ t('marketing.preview.compliance_note') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
