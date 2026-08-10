<script setup lang="ts">
import { Form, Head, Link, setLayoutProps } from '@inertiajs/vue3';
import { LockKeyhole, UserRound } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import HajjOfficeLoginPreview from '@/components/auth/HajjOfficeLoginPreview.vue';
import InputError from '@/components/InputError.vue';
import AccessHeader from '@/components/marketing/AccessHeader.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({ layout: MarketingLayout });

setLayoutProps({ accessPage: true });

const { t } = useI18n();

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const benefits = ['workspace', 'permissions', 'audit'];
</script>

<template>
    <Head :title="t('auth.login.title')" />

    <div class="min-h-screen bg-marketing-cream text-marketing-teal-dark">
        <AccessHeader />

        <main class="mx-auto max-w-7xl px-6 pt-6 pb-10 xl:px-8">
            <div
                dir="ltr"
                class="grid min-h-[45rem] overflow-hidden rounded-[1.75rem] border border-marketing-border bg-white lg:grid-cols-[0.64fr_0.36fr]"
            >
                <section
                    dir="auto"
                    class="flex items-center justify-center p-8 lg:p-12 xl:p-16"
                >
                    <div class="w-full max-w-xl">
                        <span
                            class="inline-flex rounded-full border border-marketing-teal/20 bg-marketing-teal/5 px-4 py-2 text-xs font-semibold text-marketing-teal"
                        >
                            {{ t('auth.login.office_badge') }}
                        </span>
                        <h1 class="mt-5 text-4xl font-bold">
                            {{ t('auth.login.title') }}
                        </h1>
                        <p
                            class="mt-3 max-w-lg text-sm leading-7 text-marketing-muted"
                        >
                            {{ t('auth.login.description') }}
                        </p>

                        <div
                            v-if="status"
                            class="mt-5 rounded-xl bg-success/10 px-4 py-3 text-sm font-medium text-success"
                            role="status"
                        >
                            {{ status }}
                        </div>

                        <Form
                            v-bind="store.form()"
                            :reset-on-success="['password']"
                            v-slot="{ errors, processing }"
                            class="mt-8 grid gap-6"
                        >
                            <div class="grid gap-2.5">
                                <Label
                                    for="email"
                                    class="font-semibold text-marketing-teal-dark"
                                >
                                    {{ t('auth.login.identifier_label') }}
                                </Label>
                                <div class="relative">
                                    <UserRound
                                        class="pointer-events-none absolute inset-y-0 start-4 my-auto size-5 text-marketing-teal/65"
                                        aria-hidden="true"
                                    />
                                    <Input
                                        id="email"
                                        type="text"
                                        name="email"
                                        required
                                        autofocus
                                        :tabindex="1"
                                        autocomplete="username"
                                        :placeholder="
                                            t(
                                                'auth.login.identifier_placeholder',
                                            )
                                        "
                                        :aria-invalid="Boolean(errors.email)"
                                        class="h-12 rounded-xl border-marketing-border bg-marketing-cream/65 px-11 text-sm text-marketing-teal-dark shadow-none placeholder:text-marketing-muted/65 hover:border-marketing-teal/35 focus-visible:border-marketing-teal focus-visible:ring-marketing-teal/15 dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                    />
                                </div>
                                <InputError :message="errors.email" />
                            </div>

                            <div class="grid gap-2.5">
                                <Label
                                    for="password"
                                    class="font-semibold text-marketing-teal-dark"
                                >
                                    {{ t('auth.login.password_label') }}
                                </Label>
                                <div class="relative">
                                    <LockKeyhole
                                        class="pointer-events-none absolute inset-y-0 start-4 z-10 my-auto size-5 text-marketing-teal/65"
                                        aria-hidden="true"
                                    />
                                    <PasswordInput
                                        id="password"
                                        name="password"
                                        required
                                        :tabindex="2"
                                        autocomplete="current-password"
                                        :aria-invalid="Boolean(errors.password)"
                                        class="h-12 rounded-xl border-marketing-border bg-marketing-cream/65 px-11 text-sm text-marketing-teal-dark shadow-none hover:border-marketing-teal/35 focus-visible:border-marketing-teal focus-visible:ring-marketing-teal/15 dark:bg-marketing-cream/65 dark:text-marketing-teal-dark"
                                    />
                                </div>
                                <InputError :message="errors.password" />
                            </div>

                            <div
                                class="flex items-center justify-between gap-4 text-sm"
                            >
                                <Label
                                    for="remember"
                                    class="cursor-pointer gap-2.5 text-marketing-muted"
                                >
                                    <Checkbox
                                        id="remember"
                                        name="remember"
                                        :tabindex="3"
                                        class="border-marketing-teal/35 data-[state=checked]:border-marketing-teal data-[state=checked]:bg-marketing-teal"
                                    />
                                    <span>{{
                                        t('auth.login.remember_me')
                                    }}</span>
                                </Label>
                                <Link
                                    v-if="canResetPassword"
                                    :href="request()"
                                    class="font-semibold text-marketing-teal underline-offset-4 hover:underline focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
                                    :tabindex="4"
                                >
                                    {{ t('auth.login.forgot_password') }}
                                </Link>
                            </div>

                            <Button
                                type="submit"
                                class="mt-1 h-12 w-full rounded-xl bg-marketing-gold text-sm font-bold text-marketing-teal-dark shadow-none hover:bg-marketing-gold/90 focus-visible:border-marketing-gold focus-visible:ring-marketing-gold/35"
                                :tabindex="5"
                                :disabled="processing"
                                data-test="login-button"
                            >
                                <Spinner v-if="processing" />
                                {{
                                    processing
                                        ? t('auth.login.processing')
                                        : t('auth.login.submit')
                                }}
                            </Button>
                        </Form>
                    </div>
                </section>

                <aside
                    dir="auto"
                    class="flex flex-col bg-marketing-teal-dark p-8 text-white lg:p-10"
                >
                    <span
                        class="w-fit rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-marketing-gold-soft"
                    >
                        {{ t('auth.login.information.badge') }}
                    </span>
                    <h2
                        class="mt-5 text-4xl leading-tight font-bold whitespace-pre-line"
                    >
                        {{ t('auth.login.information.title') }}
                    </h2>
                    <p class="mt-3 text-sm leading-7 text-white/65">
                        {{ t('auth.login.information.description') }}
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
                                t(`auth.login.information.benefits.${benefit}`)
                            }}
                        </li>
                    </ul>

                    <div
                        class="mt-auto flex items-center justify-between gap-4 rounded-xl bg-white/8 px-4 py-3 text-xs text-white/70"
                    >
                        <span>{{ t('auth.login.information.languages') }}</span>
                        <strong class="text-white">{{
                            t('auth.login.information.language_values')
                        }}</strong>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</template>
