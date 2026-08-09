<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Building2, ShieldCheck, Truck } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import RoleCard from '@/components/RoleCard.vue';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import { login } from '@/routes';

defineOptions({ layout: MarketingLayout });

const { t } = useI18n();
</script>

<template>
    <Head :title="t('access.title')" />

    <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold sm:text-3xl">
                {{ t('access.title') }}
            </h1>
            <p class="mt-2 text-muted-foreground">{{ t('access.subtitle') }}</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <RoleCard
                :icon="Building2"
                :title="t('access.roles.office.title')"
                :description="t('access.roles.office.description')"
                :badge="t('access.roles.office.badge')"
                :action-label="t('access.roles.office.action')"
                :href="login()"
            />
            <RoleCard
                :icon="Truck"
                :title="t('access.roles.service_provider.title')"
                :description="t('access.roles.service_provider.description')"
                :badge="t('access.roles.service_provider.badge')"
                :action-label="t('access.roles.service_provider.action')"
                disabled
            />
            <RoleCard
                :icon="ShieldCheck"
                :title="t('access.roles.kidana_team.title')"
                :description="t('access.roles.kidana_team.description')"
                :action-label="t('access.roles.kidana_team.action')"
                :href="login()"
            />
        </div>
    </div>
</template>
