<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { access } from '@/routes';

const { t } = useI18n();
</script>

<template>
    <section
        class="relative isolate overflow-hidden bg-marketing-teal py-20 text-white lg:py-24"
    >
        <div
            aria-hidden="true"
            class="absolute -start-28 top-8 size-72 rounded-full border border-marketing-gold/20"
        ></div>
        <div
            aria-hidden="true"
            class="absolute -end-20 bottom-[-7rem] size-72 rounded-full border border-marketing-gold/20"
        ></div>
        <div class="relative mx-auto max-w-4xl px-6 text-center">
            <span
                class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs text-marketing-gold-soft"
                >{{ t('marketing.season_cta.badge') }}</span
            >
            <h2 class="mt-6 text-4xl leading-tight font-bold lg:text-5xl">
                {{ t('marketing.season_cta.title') }}
                <span class="text-marketing-gold">{{
                    t('marketing.season_cta.emphasis')
                }}</span>
            </h2>
            <p class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-white/65">
                {{ t('marketing.season_cta.body') }}
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <Link
                    :href="access()"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-marketing-gold px-6 text-sm font-bold text-marketing-teal-dark transition-colors hover:bg-marketing-gold/85 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marketing-gold"
                    >{{ t('marketing.season_cta.primary') }}</Link
                >
                <Link
                    :href="access()"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-white/50 px-6 text-sm font-semibold text-white transition-colors hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    >{{ t('marketing.season_cta.secondary') }}</Link
                >
            </div>
        </div>
    </section>
</template>
