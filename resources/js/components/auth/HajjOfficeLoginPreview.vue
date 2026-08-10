<script setup lang="ts">
import { Building2 } from '@lucide/vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const metrics = [
    { value: '82%', key: 'readiness' },
    { value: '4', key: 'tracks' },
    { value: '13', key: 'stations' },
];
</script>

<template>
    <div class="rounded-2xl bg-white p-5 text-marketing-teal-dark">
        <div class="flex items-center gap-3">
            <span
                class="grid size-10 shrink-0 place-items-center rounded-xl bg-marketing-teal text-white"
            >
                <Building2 class="size-5" aria-hidden="true" />
            </span>
            <div>
                <h3 class="text-sm font-bold">
                    {{ t('auth.login.preview.title') }}
                </h3>
                <p class="mt-1 text-[10px] text-marketing-muted">
                    {{ t('auth.login.preview.season') }}
                </p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-3 gap-2">
            <div
                v-for="metric in metrics"
                :key="metric.key"
                class="rounded-xl bg-marketing-cream p-3 text-center"
            >
                <strong class="text-xl text-marketing-teal">
                    {{ metric.value }}
                </strong>
                <p class="mt-1 text-[9px] leading-4 text-marketing-muted">
                    {{ t(`auth.login.preview.metrics.${metric.key}`) }}
                </p>
            </div>
        </div>
    </div>
</template>
