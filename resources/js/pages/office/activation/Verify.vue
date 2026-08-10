<script setup lang="ts">
import { Form, Head, Link, router, setLayoutProps } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { REGEXP_ONLY_DIGITS } from 'vue-input-otp';
import ActivationStepper from '@/components/ActivationStepper.vue';
import HajjOfficeLoginPreview from '@/components/auth/HajjOfficeLoginPreview.vue';
import InputError from '@/components/InputError.vue';
import AccessHeader from '@/components/marketing/AccessHeader.vue';
import { Button } from '@/components/ui/button';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import account from '@/routes/activation/account';
import verify from '@/routes/activation/verify';

defineOptions({ layout: MarketingLayout });

setLayoutProps({ accessPage: true });

const { t } = useI18n();

const props = defineProps<{
    mobileNumberHint: string | null;
    otpLength: number;
    resendCooldownSeconds: number;
    debugCode: string | null;
}>();

const secondsLeft = ref(Math.max(0, props.resendCooldownSeconds));
const resending = ref(false);
let timer: ReturnType<typeof setInterval> | undefined;

const canResend = computed(() => secondsLeft.value === 0);
const formattedCountdown = computed(() => {
    const minutes = Math.floor(secondsLeft.value / 60);
    const seconds = secondsLeft.value % 60;

    return (
        String(minutes).padStart(2, '0') +
        ':' +
        String(seconds).padStart(2, '0')
    );
});

onMounted(() => {
    timer = setInterval(() => {
        secondsLeft.value = Math.max(0, secondsLeft.value - 1);
    }, 1000);
});

onBeforeUnmount(() => {
    if (timer) {
        clearInterval(timer);
    }
});

function resend(): void {
    if (!canResend.value || resending.value) {
        return;
    }

    resending.value = true;
    router.post(
        verify.resend(),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                secondsLeft.value = Math.max(0, props.resendCooldownSeconds);
            },
            onFinish: () => {
                resending.value = false;
            },
        },
    );
}

const benefits = ['secure', 'permissions', 'audit'];
</script>

<template>
    <Head :title="t('activation.verify.title')" />

    <div class="min-h-screen bg-marketing-cream text-marketing-teal-dark">
        <AccessHeader />

        <main class="mx-auto max-w-7xl px-6 pt-6 pb-10 xl:px-8">
            <div
                dir="ltr"
                class="grid min-h-[45rem] overflow-hidden rounded-[1.75rem] border border-marketing-border bg-white lg:grid-cols-[0.64fr_0.36fr]"
            >
                <section dir="auto" class="flex flex-col p-8 lg:p-10 xl:p-12">
                    <div class="mx-auto w-full max-w-2xl">
                        <span
                            class="inline-flex rounded-full border border-marketing-teal/20 bg-marketing-teal/5 px-4 py-2 text-xs font-semibold text-marketing-teal"
                        >
                            {{ t('activation.account.badge') }}
                        </span>
                        <h1 class="mt-4 text-3xl font-bold">
                            {{ t('activation.verify.title') }}
                        </h1>
                        <p
                            class="mt-2 max-w-xl text-sm leading-6 text-marketing-muted"
                        >
                            {{
                                t('activation.verify.subtitle', {
                                    hint: mobileNumberHint ?? '',
                                })
                            }}
                        </p>

                        <ActivationStepper :current-step="2" class="mt-6" />

                        <p
                            v-if="debugCode"
                            class="mt-4 rounded-lg bg-warning/10 px-3 py-2 text-center text-xs text-warning"
                        >
                            {{
                                t('activation.verify.debug_code_notice', {
                                    code: debugCode,
                                })
                            }}
                        </p>

                        <Form
                            v-bind="verify.store.form()"
                            v-slot="{ errors, processing }"
                            class="mt-8 flex flex-col items-center"
                        >
                            <label
                                for="activation_code"
                                class="text-sm font-semibold"
                            >
                                {{ t('activation.verify.code_label') }}
                            </label>
                            <div
                                dir="ltr"
                                class="mt-4 flex w-full flex-col items-center"
                            >
                                <InputOTP
                                    id="activation_code"
                                    name="code"
                                    :maxlength="otpLength"
                                    :pattern="REGEXP_ONLY_DIGITS"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    autofocus
                                    :disabled="processing"
                                    :aria-label="
                                        t('activation.verify.code_label')
                                    "
                                    :aria-invalid="Boolean(errors.code)"
                                    container-class="justify-center"
                                >
                                    <InputOTPGroup class="gap-3">
                                        <InputOTPSlot
                                            v-for="index in otpLength"
                                            :key="index"
                                            :index="index - 1"
                                            class="h-14 w-14 rounded-xl border border-marketing-border bg-marketing-cream/65 text-xl font-bold text-marketing-teal-dark shadow-none data-[active=true]:border-marketing-teal data-[active=true]:ring-3 data-[active=true]:ring-marketing-teal/15 dark:bg-marketing-cream/65"
                                        />
                                    </InputOTPGroup>
                                </InputOTP>
                                <InputError
                                    :message="errors.code"
                                    class="mt-3"
                                />
                            </div>

                            <div
                                class="mt-7 flex w-full items-center justify-between gap-4 rounded-xl bg-marketing-teal/8 px-5 py-4 text-sm"
                            >
                                <span class="text-marketing-muted">
                                    {{ t('activation.verify.resend_label') }}
                                </span>
                                <button
                                    v-if="canResend"
                                    type="button"
                                    class="inline-flex items-center gap-2 font-bold text-marketing-teal underline-offset-4 hover:underline focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal disabled:opacity-50"
                                    :disabled="resending"
                                    @click="resend"
                                >
                                    <Spinner v-if="resending" />
                                    {{
                                        resending
                                            ? t('activation.verify.resending')
                                            : t(
                                                  'activation.verify.resend_action',
                                              )
                                    }}
                                </button>
                                <span
                                    v-else
                                    class="font-bold text-marketing-teal"
                                    aria-live="polite"
                                >
                                    {{
                                        t(
                                            'activation.verify.resend_countdown',
                                            {
                                                seconds: formattedCountdown,
                                            },
                                        )
                                    }}
                                </span>
                            </div>

                            <Button
                                type="submit"
                                class="mt-5 h-12 w-full rounded-xl bg-marketing-gold font-bold text-marketing-teal-dark shadow-none hover:bg-marketing-gold/90 focus-visible:border-marketing-gold focus-visible:ring-marketing-gold/35"
                                :disabled="processing"
                            >
                                <Spinner v-if="processing" />
                                {{
                                    processing
                                        ? t('activation.verify.processing')
                                        : t('activation.verify.submit')
                                }}
                            </Button>
                        </Form>
                    </div>

                    <div
                        class="mx-auto mt-auto flex w-full max-w-2xl items-center justify-between gap-5 rounded-xl bg-marketing-teal/8 px-5 py-3 text-sm"
                    >
                        <Link
                            :href="account.show()"
                            class="font-semibold text-marketing-teal-dark hover:text-marketing-teal focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
                        >
                            {{ t('activation.verify.edit_activation') }}
                        </Link>
                        <span class="font-bold text-marketing-teal">
                            {{ t('common.contact_support') }}
                        </span>
                    </div>
                </section>

                <aside
                    dir="auto"
                    class="flex flex-col bg-marketing-teal-dark p-8 text-white lg:p-10"
                >
                    <span
                        class="w-fit rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-marketing-gold-soft"
                    >
                        {{ t('activation.account.information.badge') }}
                    </span>
                    <h2
                        class="mt-5 text-4xl leading-tight font-bold whitespace-pre-line"
                    >
                        {{ t('activation.verify.information.title') }}
                    </h2>
                    <p class="mt-3 text-sm leading-7 text-white/65">
                        {{ t('activation.verify.information.description') }}
                    </p>

                    <div class="mt-6">
                        <HajjOfficeLoginPreview />
                    </div>

                    <ul class="mt-6 grid gap-3 text-xs leading-5 text-white/75">
                        <li
                            v-for="benefit in benefits"
                            :key="benefit"
                            class="flex items-start gap-3"
                        >
                            <span
                                class="mt-1 grid size-4 shrink-0 place-items-center rounded-full bg-marketing-gold text-[10px] font-bold text-marketing-teal-dark"
                                aria-hidden="true"
                                >✓</span
                            >
                            {{
                                t(
                                    'activation.verify.information.benefits.' +
                                        benefit,
                                )
                            }}
                        </li>
                    </ul>

                    <div
                        class="mt-auto flex items-center justify-between gap-4 rounded-xl bg-white/8 px-4 py-3 text-xs text-white/70"
                    >
                        <span>{{
                            t('activation.account.information.languages')
                        }}</span>
                        <strong class="text-white">{{
                            t('activation.account.information.language_values')
                        }}</strong>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</template>
