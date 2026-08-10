<script setup lang="ts">
import { Form, Head, Link, setLayoutProps } from '@inertiajs/vue3';
import { Info, Mail, Phone, TicketCheck } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import ActivationStepper from '@/components/ActivationStepper.vue';
import HajjOfficeLoginPreview from '@/components/auth/HajjOfficeLoginPreview.vue';
import InputError from '@/components/InputError.vue';
import AccessHeader from '@/components/marketing/AccessHeader.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import { login } from '@/routes';
import account from '@/routes/activation/account';

defineOptions({ layout: MarketingLayout });

setLayoutProps({ accessPage: true });

const { t } = useI18n();

defineProps<{
    officeName: string;
}>();

const benefits = ['secure', 'delegate', 'audit'];
</script>

<template>
    <Head :title="t('activation.account.title')" />

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
                            {{ t('activation.account.title') }}
                        </h1>
                        <p
                            class="mt-2 max-w-xl text-sm leading-6 text-marketing-muted"
                        >
                            {{ t('activation.account.subtitle') }}
                        </p>

                        <ActivationStepper :current-step="1" class="mt-6" />

                        <div
                            class="mt-5 flex items-start gap-3 rounded-xl bg-marketing-teal/8 px-4 py-3 text-xs leading-5 text-marketing-teal-dark"
                            role="note"
                        >
                            <Info
                                class="mt-0.5 size-4 shrink-0 text-marketing-teal"
                                aria-hidden="true"
                            />
                            <span>{{ t('activation.account.alert') }}</span>
                        </div>

                        <Form
                            v-bind="account.store.form()"
                            v-slot="{ errors, processing }"
                            class="mt-5 grid gap-4"
                        >
                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <Label
                                        for="invitation_reference"
                                        class="font-semibold"
                                    >
                                        {{
                                            t(
                                                'activation.account.invitation_reference_label',
                                            )
                                        }}
                                    </Label>
                                    <div class="relative">
                                        <TicketCheck
                                            class="pointer-events-none absolute inset-y-0 start-4 my-auto size-4 text-marketing-teal/65"
                                            aria-hidden="true"
                                        />
                                        <Input
                                            id="invitation_reference"
                                            :model-value="officeName"
                                            readonly
                                            tabindex="-1"
                                            class="h-11 rounded-xl border-marketing-border bg-marketing-cream/65 px-10 text-sm text-marketing-teal-dark shadow-none dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                        />
                                    </div>
                                </div>

                                <div class="grid gap-2">
                                    <Label
                                        for="mobile_number"
                                        class="font-semibold"
                                    >
                                        {{
                                            t(
                                                'activation.account.mobile_number_label',
                                            )
                                        }}
                                    </Label>
                                    <div class="relative">
                                        <Phone
                                            class="pointer-events-none absolute inset-y-0 start-4 my-auto size-4 text-marketing-teal/65"
                                            aria-hidden="true"
                                        />
                                        <Input
                                            id="mobile_number"
                                            type="tel"
                                            name="mobile_number"
                                            required
                                            autofocus
                                            dir="ltr"
                                            autocomplete="tel"
                                            :placeholder="
                                                t(
                                                    'activation.account.mobile_number_placeholder',
                                                )
                                            "
                                            :aria-invalid="
                                                Boolean(errors.mobile_number)
                                            "
                                            class="h-11 rounded-xl border-marketing-border bg-marketing-cream/65 px-10 text-sm text-marketing-teal-dark shadow-none placeholder:text-marketing-muted/65 hover:border-marketing-teal/35 focus-visible:border-marketing-teal focus-visible:ring-marketing-teal/15 dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                        />
                                    </div>
                                    <InputError
                                        :message="errors.mobile_number"
                                    />
                                </div>
                            </div>

                            <div class="grid gap-2">
                                <Label
                                    for="official_email"
                                    class="font-semibold"
                                >
                                    {{
                                        t(
                                            'activation.account.official_email_label',
                                        )
                                    }}
                                </Label>
                                <div class="relative">
                                    <Mail
                                        class="pointer-events-none absolute inset-y-0 start-4 my-auto size-4 text-marketing-teal/65"
                                        aria-hidden="true"
                                    />
                                    <Input
                                        id="official_email"
                                        type="email"
                                        name="official_email"
                                        required
                                        dir="ltr"
                                        autocomplete="email"
                                        :placeholder="
                                            t(
                                                'activation.account.official_email_placeholder',
                                            )
                                        "
                                        :aria-invalid="
                                            Boolean(errors.official_email)
                                        "
                                        class="h-11 rounded-xl border-marketing-border bg-marketing-cream/65 px-10 text-sm text-marketing-teal-dark shadow-none placeholder:text-marketing-muted/65 hover:border-marketing-teal/35 focus-visible:border-marketing-teal focus-visible:ring-marketing-teal/15 dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                    />
                                </div>
                                <InputError :message="errors.official_email" />
                            </div>

                            <div>
                                <Label
                                    for="terms_accepted"
                                    class="cursor-pointer items-start gap-3 text-sm leading-6 font-normal text-marketing-muted"
                                >
                                    <Checkbox
                                        id="terms_accepted"
                                        name="terms_accepted"
                                        class="mt-1 border-marketing-teal/35 data-[state=checked]:border-marketing-teal data-[state=checked]:bg-marketing-teal"
                                        :aria-invalid="
                                            Boolean(errors.terms_accepted)
                                        "
                                    />
                                    <span>{{
                                        t('activation.account.terms_label')
                                    }}</span>
                                </Label>
                                <InputError
                                    :message="errors.terms_accepted"
                                    class="mt-1"
                                />
                            </div>

                            <Button
                                type="submit"
                                class="h-11 w-full rounded-xl bg-marketing-gold font-bold text-marketing-teal-dark shadow-none hover:bg-marketing-gold/90 focus-visible:border-marketing-gold focus-visible:ring-marketing-gold/35"
                                :disabled="processing"
                            >
                                <Spinner v-if="processing" />
                                {{
                                    processing
                                        ? t('activation.account.processing')
                                        : t('activation.account.submit')
                                }}
                            </Button>
                        </Form>
                    </div>

                    <div
                        class="mx-auto mt-auto flex w-full max-w-2xl items-center justify-between gap-5 rounded-xl bg-marketing-teal/8 px-5 py-3 text-sm"
                    >
                        <Link
                            :href="login()"
                            class="font-semibold text-marketing-teal-dark hover:text-marketing-teal focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
                        >
                            {{ t('activation.account.back_to_login') }}
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
                        {{ t('activation.account.information.title') }}
                    </h2>
                    <p class="mt-3 text-sm leading-7 text-white/65">
                        {{ t('activation.account.information.description') }}
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
                                    `activation.account.information.benefits.${benefit}`,
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
