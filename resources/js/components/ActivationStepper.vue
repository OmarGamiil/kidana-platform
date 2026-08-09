<script setup lang="ts">
import { Check } from '@lucide/vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    currentStep: 1 | 2 | 3;
}>();

const { t } = useI18n();

const steps = [
    { step: 1, labelKey: 'activation.steps.account' },
    { step: 2, labelKey: 'activation.steps.verify' },
    { step: 3, labelKey: 'activation.steps.password' },
] as const;
</script>

<template>
    <ol class="mb-2 flex items-center justify-center gap-2">
        <li
            v-for="(item, index) in steps"
            :key="item.step"
            class="flex items-center gap-2"
        >
            <span
                :class="[
                    'flex size-7 items-center justify-center rounded-full text-xs font-semibold',
                    item.step < props.currentStep &&
                        'bg-primary text-primary-foreground',
                    item.step === props.currentStep &&
                        'border-2 border-primary text-primary',
                    item.step > props.currentStep &&
                        'border border-border text-muted-foreground',
                ]"
            >
                <Check v-if="item.step < props.currentStep" class="size-4" />
                <template v-else>{{ item.step }}</template>
            </span>
            <span
                :class="[
                    'text-xs font-medium',
                    item.step === props.currentStep
                        ? 'text-foreground'
                        : 'text-muted-foreground',
                ]"
            >
                {{ t(item.labelKey) }}
            </span>
            <span
                v-if="index < steps.length - 1"
                class="mx-1 h-px w-6 bg-border"
            />
        </li>
    </ol>
</template>
