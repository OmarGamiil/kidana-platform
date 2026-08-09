<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    BedDouble,
    ClipboardCheck,
    LayoutDashboard,
    Tent,
    Truck,
    UtensilsCrossed,
} from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import { access } from '@/routes';

defineOptions({ layout: MarketingLayout });

const { t, locale } = useI18n();

const tracks = [
    { icon: Tent, key: 'camps' },
    { icon: BedDouble, key: 'housing' },
    { icon: UtensilsCrossed, key: 'catering' },
    { icon: Truck, key: 'transport' },
];

const stats = [
    { value: '13', key: 'stations' },
    { value: '4', key: 'tracks' },
    { value: '6', key: 'stages' },
];

// Exact step copy pending confirmation against the Figma "how it works" section —
// this is a structurally faithful placeholder (season lifecycle, 6 stages).
const howItWorksSteps = [
    { ar: 'الدراسة والمراجعة', en: 'Study & review' },
    { ar: 'تخطيط المشروع', en: 'Project planning' },
    { ar: 'الفحص الميداني', en: 'Field inspection' },
    { ar: 'متابعة المعالجة', en: 'Remediation follow-up' },
    { ar: 'التشغيل الموسمي', en: 'Seasonal operation' },
    { ar: 'الاستلام والتقارير', en: 'Handover & reports' },
];
</script>

<template>
    <Head :title="t('marketing.hero.title')" />

    <!-- Hero -->
    <section
        class="border-b border-border bg-gradient-to-b from-primary/5 to-transparent"
    >
        <div class="mx-auto max-w-6xl px-4 py-20 text-center sm:px-6">
            <h1 class="text-3xl font-bold tracking-tight sm:text-5xl">
                {{ t('marketing.hero.title') }}
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-muted-foreground">
                {{ t('marketing.hero.subtitle') }}
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <Link
                    :href="access()"
                    class="inline-flex h-11 items-center justify-center rounded-md bg-primary px-6 text-sm font-semibold text-primary-foreground hover:bg-primary/90"
                >
                    {{ t('marketing.hero.cta_primary') }}
                </Link>
                <a
                    href="#package"
                    class="inline-flex h-11 items-center justify-center rounded-md border border-border px-6 text-sm font-semibold hover:bg-accent hover:text-accent-foreground"
                >
                    {{ t('marketing.hero.cta_secondary') }}
                </a>
            </div>

            <div
                class="mx-auto mt-14 grid max-w-2xl grid-cols-3 gap-6 border-t border-border pt-8"
            >
                <div v-for="stat in stats" :key="stat.key">
                    <div class="text-3xl font-bold text-primary">
                        {{ stat.value }}
                    </div>
                    <div class="mt-1 text-sm text-muted-foreground">
                        {{ t(`marketing.stats.${stat.key}`) }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why subscribe -->
    <section class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6">
        <LayoutDashboard class="mx-auto mb-4 size-10 text-primary" />
        <h2 class="text-2xl font-bold">{{ t('marketing.why.title') }}</h2>
        <p class="mt-3 text-muted-foreground">{{ t('marketing.why.body') }}</p>
    </section>

    <!-- Service tracks -->
    <section class="border-y border-border bg-muted/30">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h2 class="mb-10 text-center text-2xl font-bold">
                {{ t('marketing.tracks.title') }}
            </h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <Card
                    v-for="track in tracks"
                    :key="track.key"
                    class="text-center"
                >
                    <CardHeader>
                        <div
                            class="mx-auto flex size-12 items-center justify-center rounded-lg bg-primary/10 text-primary"
                        >
                            <component :is="track.icon" class="size-6" />
                        </div>
                        <CardTitle class="mt-3">
                            {{ t(`marketing.tracks.${track.key}`) }}
                        </CardTitle>
                    </CardHeader>
                </Card>
            </div>
        </div>
    </section>

    <!-- Package -->
    <section id="package" class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <Card class="border-primary/30">
            <CardHeader class="text-center">
                <Badge class="mx-auto mb-2 w-fit">{{
                    t('marketing.package.title')
                }}</Badge>
                <CardTitle class="text-2xl">{{
                    t('marketing.package.title')
                }}</CardTitle>
            </CardHeader>
            <CardContent class="text-center text-muted-foreground">
                {{ t('marketing.package.description') }}
            </CardContent>
        </Card>
    </section>

    <!-- How it works -->
    <section class="border-y border-border bg-muted/30">
        <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6">
            <h2 class="mb-10 text-center text-2xl font-bold">
                {{ t('marketing.how_it_works.title') }}
            </h2>
            <ol class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="(step, index) in howItWorksSteps"
                    :key="index"
                    class="flex items-start gap-3 rounded-lg border border-border bg-background p-4"
                >
                    <span
                        class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary text-sm font-semibold text-primary-foreground"
                    >
                        {{ index + 1 }}
                    </span>
                    <span class="pt-1 text-sm font-medium">
                        {{ locale === 'ar' ? step.ar : step.en }}
                    </span>
                </li>
            </ol>
        </div>
    </section>

    <!-- Dashboard preview -->
    <section class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6">
        <ClipboardCheck class="mx-auto mb-4 size-10 text-primary" />
        <h2 class="text-2xl font-bold">
            {{ t('marketing.dashboard_preview.title') }}
        </h2>
        <div
            class="mt-8 rounded-xl border border-border bg-muted/40 p-10 text-sm text-muted-foreground"
        >
            {{ t('marketing.dashboard_preview.title') }}
        </div>
    </section>

    <!-- Final CTA -->
    <section class="bg-primary text-primary-foreground">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6">
            <h2 class="text-2xl font-bold sm:text-3xl">
                {{ t('marketing.final_cta.title') }}
            </h2>
            <Link
                :href="access()"
                class="mt-6 inline-flex h-11 items-center justify-center rounded-md bg-background px-6 text-sm font-semibold text-foreground hover:bg-background/90"
            >
                {{ t('marketing.final_cta.cta') }}
            </Link>
        </div>
    </section>

    <!-- Free knowledge account -->
    <section class="mx-auto max-w-3xl px-4 py-16 text-center sm:px-6">
        <h2 class="text-xl font-bold">
            {{ t('marketing.free_account.title') }}
        </h2>
        <p class="mx-auto mt-3 max-w-xl text-sm text-muted-foreground">
            {{ t('marketing.free_account.description') }}
        </p>
        <a
            href="#"
            class="mt-6 inline-flex h-10 items-center justify-center rounded-md border border-border px-5 text-sm font-medium hover:bg-accent hover:text-accent-foreground"
        >
            {{ t('marketing.free_account.cta') }}
        </a>
    </section>
</template>
