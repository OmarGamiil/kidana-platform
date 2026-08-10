<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import BasicPackageSection from '@/components/marketing/BasicPackageSection.vue';
import DashboardShowcaseSection from '@/components/marketing/DashboardShowcaseSection.vue';
import MarketingCapabilities from '@/components/marketing/MarketingCapabilities.vue';
import MarketingFooter from '@/components/marketing/MarketingFooter.vue';
import MarketingHero from '@/components/marketing/MarketingHero.vue';
import SeasonCtaSection from '@/components/marketing/SeasonCtaSection.vue';
import SeasonWorkflowSection from '@/components/marketing/SeasonWorkflowSection.vue';
import WhyKidanaSection from '@/components/marketing/WhyKidanaSection.vue';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';

defineOptions({ layout: MarketingLayout });

setLayoutProps({ homepage: true });

const { t } = useI18n();
</script>

<template>
    <Head :title="t('marketing.hero.page_title')" />
    <div>
        <MarketingHero />
        <MarketingCapabilities />
        <WhyKidanaSection />
        <BasicPackageSection />
        <SeasonWorkflowSection />
        <DashboardShowcaseSection />
        <SeasonCtaSection />
        <MarketingFooter />
    </div>
</template>
