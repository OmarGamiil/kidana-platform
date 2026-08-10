<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const steps = [
    'study',
    'planning',
    'inspection',
    'remediation',
    'operation',
    'handover',
];
</script>

<template>
    <section class="bg-marketing-teal-dark py-20 text-white lg:py-24">
        <div class="mx-auto max-w-7xl px-6 xl:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold">
                    {{ t('marketing.workflow.title') }}
                </h2>
                <p class="mt-3 text-sm text-white/55">
                    {{ t('marketing.workflow.subtitle') }}
                </p>
            </div>
            <ol class="mt-12 grid grid-cols-3 gap-3 lg:grid-cols-6">
                <li
                    v-for="(step, index) in steps"
                    :key="step"
                    class="min-h-56 rounded-2xl border border-white/5 bg-marketing-teal p-5"
                >
                    <strong class="text-xl text-marketing-gold">{{
                        String(index + 1).padStart(2, '0')
                    }}</strong>
                    <h3 class="mt-7 text-base leading-7 font-bold">
                        {{ t(`marketing.workflow.steps.${step}.title`) }}
                    </h3>
                    <p class="mt-3 text-xs leading-6 text-white/55">
                        {{ t(`marketing.workflow.steps.${step}.body`) }}
                    </p>
                </li>
            </ol>
        </div>
    </section>
</template>
