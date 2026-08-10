<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import MarketingCapabilityCard from '@/components/marketing/MarketingCapabilityCard.vue';

const { t } = useI18n();

const capabilities = [
    { value: '13', key: 'stations' },
    { value: '4', key: 'tracks' },
    { value: '6', key: 'stages' },
    { valueKey: 'languages_value', key: 'languages', accent: true },
];
</script>

<template>
    <section id="capabilities" class="bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-6 xl:px-8">
            <h2
                class="mx-auto max-w-3xl text-center text-2xl leading-relaxed font-bold text-marketing-teal-dark lg:text-3xl"
            >
                {{ t('marketing.capabilities.title') }}
            </h2>
            <div class="mt-10 grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-5">
                <MarketingCapabilityCard
                    v-for="capability in capabilities"
                    :key="capability.key"
                    :value="
                        capability.value ??
                        t(`marketing.capabilities.${capability.valueKey}`)
                    "
                    :label="t(`marketing.capabilities.${capability.key}`)"
                    :accent="capability.accent"
                />
            </div>
        </div>
    </section>
</template>
