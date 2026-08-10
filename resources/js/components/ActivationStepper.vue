<script setup lang="ts">
import { Check } from '@lucide/vue';
import { computed } from 'vue';
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

const progressWidth = computed(() => (props.currentStep - 1) * 33.34 + '%');
</script>

<template>
    <div class="relative">
        <span
            class="absolute inset-x-[16.66%] top-4 h-px bg-marketing-border"
            aria-hidden="true"
        ></span>
        <span
            class="absolute top-4 h-px bg-marketing-gold transition-[width]"
            :style="{
                insetInlineStart: '16.66%',
                width: progressWidth,
            }"
            aria-hidden="true"
        ></span>
        <ol
            class="relative grid grid-cols-3"
            :aria-label="t('activation.steps.label')"
        >
            <li
                v-for="item in steps"
                :key="item.step"
                class="relative flex flex-col items-center text-center"
                :aria-current="
                    item.step === props.currentStep ? 'step' : undefined
                "
            >
                <span
                    :class="[
                        'z-10 grid size-8 place-items-center rounded-full border text-xs font-bold',
                        item.step < props.currentStep &&
                            'border-marketing-gold bg-marketing-gold text-marketing-teal-dark',
                        item.step === props.currentStep &&
                            'border-marketing-teal bg-marketing-teal text-white',
                        item.step > props.currentStep &&
                            'border-marketing-border bg-white text-marketing-muted',
                    ]"
                >
                    <Check
                        v-if="item.step < props.currentStep"
                        class="size-4"
                        aria-hidden="true"
                    />
                    <template v-else>
                        {{ String(item.step).padStart(2, '0') }}
                    </template>
                </span>
                <span
                    :class="[
                        'mt-2 text-[11px] font-semibold',
                        item.step === props.currentStep
                            ? 'text-marketing-teal-dark'
                            : 'text-marketing-muted',
                    ]"
                >
                    {{ t(item.labelKey) }}
                </span>
            </li>
        </ol>
    </div>
</template>
