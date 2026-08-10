<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { access } from '@/routes';

const { t } = useI18n();

const services = ['camps', 'housing', 'catering', 'transport'];
</script>

<template>
    <section class="bg-marketing-cream pb-24">
        <div
            class="mx-auto grid max-w-7xl gap-6 px-6 lg:grid-cols-[0.72fr_1.6fr] xl:px-8"
        >
            <article
                class="order-2 flex min-h-[34rem] flex-col justify-center rounded-3xl bg-marketing-teal p-9 text-white lg:order-1"
            >
                <span
                    class="w-fit rounded-full border border-white/25 px-3 py-1.5 text-xs text-white/75"
                    >{{ t('marketing.package_section.badge') }}</span
                >
                <h2
                    class="mt-6 text-4xl leading-tight font-bold text-marketing-gold"
                >
                    {{ t('marketing.package_section.title') }}
                </h2>
                <p class="mt-5 text-sm leading-7 text-white/75">
                    {{ t('marketing.package_section.body') }}
                </p>
                <p class="mt-7 font-semibold">
                    {{ t('marketing.package_section.summary') }}
                </p>
                <Link
                    :href="access()"
                    class="mt-8 inline-flex h-11 items-center justify-center rounded-lg bg-marketing-gold px-5 text-sm font-bold text-marketing-teal-dark transition-colors hover:bg-marketing-gold/85 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-gold"
                >
                    {{ t('marketing.package_section.cta') }}
                </Link>
            </article>

            <div class="order-1 grid grid-cols-2 gap-5 lg:order-2">
                <article
                    v-for="(service, index) in services"
                    :key="service"
                    class="flex min-h-64 flex-col rounded-2xl border border-marketing-border bg-white p-7"
                >
                    <span
                        class="flex size-8 items-center justify-center rounded-full bg-marketing-cream text-xs font-bold text-marketing-gold"
                        >{{ String(index + 1).padStart(2, '0') }}</span
                    >
                    <h3 class="mt-8 text-xl font-bold text-marketing-teal">
                        {{
                            t(
                                `marketing.package_section.services.${service}.title`,
                            )
                        }}
                    </h3>
                    <p class="mt-2 text-sm font-medium text-marketing-gold">
                        {{
                            t(
                                `marketing.package_section.services.${service}.tagline`,
                            )
                        }}
                    </p>
                    <p class="mt-5 text-sm leading-7 text-marketing-muted">
                        {{
                            t(
                                `marketing.package_section.services.${service}.body`,
                            )
                        }}
                    </p>
                </article>
            </div>
        </div>
    </section>
</template>
