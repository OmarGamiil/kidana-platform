<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BellRing, Check, FileSearch, ShieldCheck } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { access } from '@/routes';

const { t } = useI18n();

const certificateItems = ['requirements', 'tracking', 'documentation'];
</script>

<template>
    <section class="bg-marketing-cream py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 xl:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-marketing-teal-dark">
                    {{ t('marketing.why_kidana.title') }}
                </h2>
                <p class="mt-3 text-sm text-marketing-muted">
                    {{ t('marketing.why_kidana.subtitle') }}
                </p>
            </div>

            <div
                class="mt-12 grid items-stretch gap-6 lg:grid-cols-[1.08fr_1fr]"
            >
                <div class="order-2 grid gap-5">
                    <article
                        class="grid min-h-48 grid-cols-[1fr_8.5rem] items-center gap-5 rounded-2xl border border-marketing-border bg-white p-6"
                    >
                        <div>
                            <h3 class="text-xl font-bold text-marketing-teal">
                                {{ t('marketing.why_kidana.mobile.title') }}
                            </h3>
                            <p
                                class="mt-3 text-sm leading-7 text-marketing-muted"
                            >
                                {{ t('marketing.why_kidana.mobile.body') }}
                            </p>
                        </div>
                        <div
                            class="mx-auto w-24 rounded-[1.35rem] border-[5px] border-marketing-teal-dark bg-marketing-surface p-2 shadow-md"
                        >
                            <div
                                class="mx-auto h-1 w-8 rounded-full bg-marketing-teal-dark/30"
                            ></div>
                            <p
                                class="mt-4 text-center text-[9px] text-marketing-muted"
                            >
                                {{ t('marketing.why_kidana.mobile.readiness') }}
                            </p>
                            <strong
                                class="mt-1 block text-center text-2xl text-marketing-teal"
                                >82%</strong
                            >
                            <div
                                class="mt-3 h-1.5 overflow-hidden rounded-full bg-marketing-border"
                            >
                                <div
                                    class="h-full w-[82%] rounded-full bg-marketing-gold"
                                ></div>
                            </div>
                            <div
                                class="mt-4 rounded-lg bg-white px-2 py-3 text-center text-[8px] text-marketing-teal"
                            >
                                {{ t('marketing.why_kidana.mobile.status') }}
                            </div>
                        </div>
                    </article>

                    <div class="grid grid-cols-2 gap-5">
                        <article
                            class="rounded-2xl border border-marketing-border bg-white p-5"
                        >
                            <FileSearch class="size-5 text-marketing-teal" />
                            <h3 class="mt-4 font-bold text-marketing-teal-dark">
                                {{ t('marketing.why_kidana.audit.title') }}
                            </h3>
                            <p
                                class="mt-2 text-xs leading-6 text-marketing-muted"
                            >
                                {{ t('marketing.why_kidana.audit.body') }}
                            </p>
                        </article>
                        <article
                            class="rounded-2xl border border-marketing-border bg-white p-5"
                        >
                            <ShieldCheck class="size-5 text-marketing-gold" />
                            <h3 class="mt-4 font-bold text-marketing-teal-dark">
                                {{ t('marketing.why_kidana.compliance.title') }}
                            </h3>
                            <p
                                class="mt-2 text-xs leading-6 text-marketing-muted"
                            >
                                {{ t('marketing.why_kidana.compliance.body') }}
                            </p>
                        </article>
                    </div>
                </div>

                <article
                    class="order-1 flex min-h-[25rem] flex-col rounded-3xl bg-marketing-teal-dark p-8 text-white shadow-lg shadow-marketing-teal-dark/10 lg:p-10"
                >
                    <div
                        class="inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs text-marketing-gold-soft"
                    >
                        <BellRing class="size-3.5" />
                        {{ t('marketing.why_kidana.certificate.badge') }}
                    </div>
                    <h3 class="mt-6 max-w-lg text-3xl leading-snug font-bold">
                        {{ t('marketing.why_kidana.certificate.title') }}
                    </h3>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-white/70">
                        {{ t('marketing.why_kidana.certificate.body') }}
                    </p>
                    <ul class="mt-6 grid gap-3 text-sm">
                        <li
                            v-for="item in certificateItems"
                            :key="item"
                            class="flex items-center gap-3"
                        >
                            <span
                                class="flex size-5 items-center justify-center rounded-full bg-marketing-gold text-marketing-teal-dark"
                            >
                                <Check class="size-3.5" />
                            </span>
                            {{
                                t(
                                    `marketing.why_kidana.certificate.items.${item}`,
                                )
                            }}
                        </li>
                    </ul>
                    <Link
                        :href="access()"
                        class="mt-auto inline-flex min-h-11 items-center justify-center rounded-lg bg-marketing-gold px-5 text-sm font-bold text-marketing-teal-dark transition-colors hover:bg-marketing-gold/85 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-gold"
                    >
                        {{ t('marketing.why_kidana.certificate.cta') }}
                    </Link>
                </article>
            </div>
        </div>
    </section>
</template>
