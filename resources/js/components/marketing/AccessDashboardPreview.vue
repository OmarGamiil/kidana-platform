<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const metrics = [
    { value: '13', key: 'stations' },
    { value: '6', key: 'stages' },
    { value: '94', key: 'reports' },
];
</script>

<template>
    <div
        class="rounded-2xl bg-white p-5 text-marketing-teal-dark shadow-lg shadow-black/10"
    >
        <div class="flex items-center justify-between gap-4">
            <h3 class="text-sm font-bold">{{ t('access.preview.title') }}</h3>
            <span
                class="rounded-full bg-marketing-teal/10 px-3 py-1 text-[10px] font-bold text-marketing-teal"
                >{{ t('access.preview.live') }}</span
            >
        </div>
        <div class="mt-4 rounded-xl bg-marketing-cream p-4">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold text-marketing-muted">
                        {{ t('access.preview.readiness') }}
                    </p>
                    <strong class="mt-1 block text-3xl text-marketing-teal"
                        >82%</strong
                    >
                </div>
                <span class="text-[10px] text-marketing-teal">{{
                    t('access.preview.status')
                }}</span>
            </div>
            <div class="mt-4 h-2 overflow-hidden rounded-full bg-white">
                <div
                    class="h-full w-[82%] rounded-full bg-marketing-gold"
                ></div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2">
            <div
                v-for="metric in metrics"
                :key="metric.key"
                class="rounded-lg border border-marketing-border bg-marketing-surface p-3 text-center"
            >
                <strong class="text-lg text-marketing-teal">{{
                    metric.value
                }}</strong>
                <p class="mt-1 text-[9px] leading-4 text-marketing-muted">
                    {{ t(`access.preview.metrics.${metric.key}`) }}
                </p>
            </div>
        </div>
    </div>
</template>
