<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import ActivationStepper from '@/components/ActivationStepper.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import verify from '@/routes/activation/verify';

defineOptions({ layout: AuthLayout });

const { t } = useI18n();

const props = defineProps<{
    mobileNumberHint: string | null;
    otpLength: number;
    resendCooldownSeconds: number;
    debugCode: string | null;
}>();

const secondsLeft = ref(props.resendCooldownSeconds);
let timer: ReturnType<typeof setInterval> | undefined;

onMounted(() => {
    timer = setInterval(() => {
        if (secondsLeft.value > 0) {
            secondsLeft.value -= 1;
        }
    }, 1000);
});

onBeforeUnmount(() => {
    clearInterval(timer);
});

const canResend = computed(() => secondsLeft.value <= 0);
const resending = ref(false);

function resend() {
    resending.value = true;
    router.post(
        verify.resend().url,
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                resending.value = false;
                secondsLeft.value = props.resendCooldownSeconds;
            },
        },
    );
}
</script>

<template>
    <Head :title="t('activation.verify.title')" />

    <ActivationStepper :current-step="2" />

    <p class="mb-4 text-center text-sm text-muted-foreground">
        {{ t('activation.verify.subtitle', { hint: mobileNumberHint ?? '' }) }}
    </p>

    <p v-if="debugCode" class="mb-4 text-center text-xs text-warning">
        {{ t('activation.verify.debug_code_notice', { code: debugCode }) }}
    </p>

    <Form
        v-bind="verify.store.form()"
        v-slot="{ errors, processing }"
        class="flex flex-col items-center gap-6"
    >
        <div class="flex flex-col items-center gap-2">
            <InputOTP
                name="code"
                :maxlength="otpLength"
                autofocus
                container-class="justify-center"
            >
                <InputOTPGroup>
                    <InputOTPSlot
                        v-for="i in otpLength"
                        :key="i"
                        :index="i - 1"
                    />
                </InputOTPGroup>
            </InputOTP>
            <InputError :message="errors.code" />
        </div>

        <Button type="submit" class="w-full" :disabled="processing">
            <Spinner v-if="processing" />
            {{ t('activation.verify.submit') }}
        </Button>

        <div class="text-sm text-muted-foreground">
            {{ t('activation.verify.resend_label') }}
            <button
                v-if="canResend"
                type="button"
                class="font-medium text-primary underline-offset-4 hover:underline"
                :disabled="resending"
                @click="resend"
            >
                {{ t('activation.verify.resend_action') }}
            </button>
            <span v-else>
                {{
                    t('activation.verify.resend_countdown', {
                        seconds: `0:${String(secondsLeft).padStart(2, '0')}`,
                    })
                }}
            </span>
        </div>
    </Form>
</template>
