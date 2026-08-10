<script setup lang="ts">
import { Form, Head, Link, setLayoutProps } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import ActivationStepper from '@/components/ActivationStepper.vue';
import HajjOfficeLoginPreview from '@/components/auth/HajjOfficeLoginPreview.vue';
import InputError from '@/components/InputError.vue';
import AccessHeader from '@/components/marketing/AccessHeader.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import passwordRoute from '@/routes/activation/password';
import verify from '@/routes/activation/verify';

defineOptions({ layout: MarketingLayout });

setLayoutProps({ accessPage: true });

const props = defineProps<{
    passwordRules: string;
}>();

const { t } = useI18n();
const password = ref('');
const passwordConfirmation = ref('');

type Requirement = {
    key: string;
    value?: number;
    isSatisfied: (value: string) => boolean;
};

const requirements = computed<Requirement[]>(() => {
    const rules: Requirement[] = [];
    const minimumLength = props.passwordRules.match(/minlength:\s*(\d+)/)?.[1];

    if (minimumLength) {
        const length = Number(minimumLength);
        rules.push({
            key: 'minimum',
            value: length,
            isSatisfied: (value) => value.length >= length,
        });
    }

    const requiresLowercase = props.passwordRules.includes('required: lower');
    const requiresUppercase = props.passwordRules.includes('required: upper');

    if (requiresLowercase && requiresUppercase) {
        rules.push({
            key: 'mixed_case',
            isSatisfied: (value) => /[a-z]/.test(value) && /[A-Z]/.test(value),
        });
    } else if (requiresLowercase) {
        rules.push({
            key: 'letter',
            isSatisfied: (value) => /[A-Za-z]/.test(value),
        });
    }

    if (props.passwordRules.includes('required: digit')) {
        rules.push({
            key: 'number',
            isSatisfied: (value) => /\d/.test(value),
        });
    }

    if (props.passwordRules.includes('required: special')) {
        rules.push({
            key: 'symbol',
            isSatisfied: (value) => /[^A-Za-z0-9]/.test(value),
        });
    }

    return rules;
});

const benefits = ['secure', 'permissions', 'audit'];
</script>

<template>
    <Head :title="t('activation.password.title')" />

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
                            {{ t('activation.password.title') }}
                        </h1>
                        <p
                            class="mt-2 max-w-xl text-sm leading-6 text-marketing-muted"
                        >
                            {{ t('activation.password.subtitle') }}
                        </p>

                        <ActivationStepper :current-step="3" class="mt-6" />

                        <Form
                            v-bind="passwordRoute.store.form()"
                            v-slot="{ errors, processing }"
                            class="mt-6 grid gap-4"
                        >
                            <div class="grid gap-2">
                                <Label for="password" class="font-semibold">
                                    {{
                                        t('activation.password.password_label')
                                    }}
                                </Label>
                                <PasswordInput
                                    id="password"
                                    v-model="password"
                                    name="password"
                                    required
                                    autofocus
                                    dir="ltr"
                                    autocomplete="new-password"
                                    :placeholder="
                                        t(
                                            'activation.password.password_placeholder',
                                        )
                                    "
                                    :aria-invalid="Boolean(errors.password)"
                                    :disabled="processing"
                                    class="h-11 rounded-xl border-marketing-border bg-marketing-cream/65 text-sm text-marketing-teal-dark shadow-none placeholder:text-marketing-muted/65 hover:border-marketing-teal/35 focus-visible:border-marketing-teal focus-visible:ring-marketing-teal/15 dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                />
                                <InputError :message="errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label
                                    for="password_confirmation"
                                    class="font-semibold"
                                >
                                    {{
                                        t(
                                            'activation.password.password_confirmation_label',
                                        )
                                    }}
                                </Label>
                                <PasswordInput
                                    id="password_confirmation"
                                    v-model="passwordConfirmation"
                                    name="password_confirmation"
                                    required
                                    dir="ltr"
                                    autocomplete="new-password"
                                    :placeholder="
                                        t(
                                            'activation.password.password_confirmation_placeholder',
                                        )
                                    "
                                    :aria-invalid="
                                        Boolean(errors.password_confirmation)
                                    "
                                    :disabled="processing"
                                    class="h-11 rounded-xl border-marketing-border bg-marketing-cream/65 text-sm text-marketing-teal-dark shadow-none placeholder:text-marketing-muted/65 hover:border-marketing-teal/35 focus-visible:border-marketing-teal focus-visible:ring-marketing-teal/15 dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                />
                                <InputError
                                    :message="errors.password_confirmation"
                                />
                            </div>

                            <div
                                class="flex min-h-16 items-center justify-between gap-4 rounded-xl bg-marketing-teal/8 px-4 py-3"
                                role="status"
                                aria-live="polite"
                            >
                                <h2 class="shrink-0 text-sm font-bold">
                                    {{
                                        t(
                                            'activation.password.requirements.title',
                                        )
                                    }}
                                </h2>
                                <ul class="flex flex-wrap justify-end gap-2">
                                    <li
                                        v-for="requirement in requirements"
                                        :key="requirement.key"
                                        :class="[
                                            'inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-xs font-semibold transition-colors',
                                            requirement.isSatisfied(password)
                                                ? 'text-marketing-teal'
                                                : 'text-marketing-muted',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'grid size-4 place-items-center rounded-full border',
                                                requirement.isSatisfied(
                                                    password,
                                                )
                                                    ? 'border-marketing-teal bg-marketing-teal text-white'
                                                    : 'border-marketing-border',
                                            ]"
                                            aria-hidden="true"
                                        >
                                            <Check
                                                v-if="
                                                    requirement.isSatisfied(
                                                        password,
                                                    )
                                                "
                                                class="size-3"
                                            />
                                        </span>
                                        {{
                                            t(
                                                `activation.password.requirements.${requirement.key}`,
                                                { count: requirement.value },
                                            )
                                        }}
                                    </li>
                                </ul>
                            </div>

                            <Button
                                type="submit"
                                class="h-12 w-full rounded-xl bg-marketing-gold font-bold text-marketing-teal-dark shadow-none hover:bg-marketing-gold/90 focus-visible:border-marketing-gold focus-visible:ring-marketing-gold/35"
                                :disabled="processing"
                            >
                                <Spinner v-if="processing" />
                                {{
                                    processing
                                        ? t('activation.password.processing')
                                        : t('activation.password.submit')
                                }}
                            </Button>
                        </Form>
                    </div>

                    <div
                        class="mx-auto mt-auto flex w-full max-w-2xl items-center justify-between gap-5 rounded-xl bg-marketing-teal/8 px-5 py-3 text-sm"
                    >
                        <Link
                            :href="verify.show()"
                            class="font-semibold text-marketing-teal-dark hover:text-marketing-teal focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
                        >
                            {{ t('activation.password.back_to_verification') }}
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
                        {{ t('activation.password.information.title') }}
                    </h2>
                    <p class="mt-3 text-sm leading-7 text-white/65">
                        {{ t('activation.password.information.description') }}
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
