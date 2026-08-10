<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';
import { Building2, ShieldCheck, Truck } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import AccessDashboardPreview from '@/components/marketing/AccessDashboardPreview.vue';
import AccessHeader from '@/components/marketing/AccessHeader.vue';
import AccessRoleCard from '@/components/marketing/AccessRoleCard.vue';
import MarketingLayout from '@/layouts/marketing/MarketingLayout.vue';
import { login } from '@/routes';

defineOptions({ layout: MarketingLayout });

setLayoutProps({ accessPage: true });

const { t } = useI18n();

const informationBenefits = ['readiness', 'reports', 'permissions'];
</script>

<template>
    <Head :title="t('access.title')" />
    <div class="min-h-screen bg-marketing-cream text-marketing-teal-dark">
        <AccessHeader />
        <main class="mx-auto max-w-7xl px-6 pt-6 pb-10 xl:px-8">
            <div
                class="grid min-h-[45rem] overflow-hidden rounded-[1.75rem] border border-marketing-border bg-white lg:grid-cols-[0.37fr_0.63fr]"
            >
                <aside
                    class="order-1 flex flex-col bg-marketing-teal-dark p-8 text-white lg:p-10"
                >
                    <span
                        class="w-fit rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-marketing-gold-soft"
                        >{{ t('access.information.badge') }}</span
                    >
                    <h2
                        class="mt-6 text-4xl leading-tight font-bold whitespace-pre-line"
                    >
                        {{ t('access.information.title') }}
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-white/65">
                        {{ t('access.information.body') }}
                    </p>
                    <ul class="mt-7 grid gap-4 text-sm leading-6 text-white/80">
                        <li
                            v-for="benefit in informationBenefits"
                            :key="benefit"
                            class="flex items-start gap-3"
                        >
                            <span
                                class="mt-2 size-2 shrink-0 rounded-full bg-marketing-gold"
                            ></span>
                            {{ t(`access.information.benefits.${benefit}`) }}
                        </li>
                    </ul>
                    <div class="mt-auto pt-8"><AccessDashboardPreview /></div>
                </aside>

                <section class="order-2 flex flex-col p-8 lg:p-10 xl:p-12">
                    <span
                        class="w-fit rounded-full border border-marketing-teal/25 bg-marketing-teal/5 px-4 py-2 text-xs font-semibold text-marketing-teal"
                        >{{ t('access.intro.badge') }}</span
                    >
                    <h1
                        class="mt-5 text-4xl font-bold text-marketing-teal-dark"
                    >
                        {{ t('access.title') }}
                    </h1>
                    <p
                        class="mt-3 max-w-2xl text-sm leading-7 text-marketing-muted"
                    >
                        {{ t('access.intro.body') }}
                    </p>

                    <div dir="ltr" class="mt-8 grid grid-cols-2 gap-4">
                        <AccessRoleCard
                            dir="auto"
                            :icon="Building2"
                            :title="t('access.roles.office.title')"
                            :description="t('access.roles.office.description')"
                            :badge="t('access.roles.office.badge')"
                            :action-label="t('access.roles.office.action')"
                            :href="login()"
                            variant="primary"
                        />
                        <AccessRoleCard
                            dir="auto"
                            :icon="Truck"
                            :title="t('access.roles.service_provider.title')"
                            :description="
                                t('access.roles.service_provider.description')
                            "
                            :badge="t('access.roles.service_provider.badge')"
                            :action-label="
                                t('access.roles.service_provider.action')
                            "
                            variant="secondary"
                            disabled
                        />
                        <AccessRoleCard
                            dir="auto"
                            :icon="ShieldCheck"
                            :title="t('access.roles.kidana_team.title')"
                            :description="
                                t('access.roles.kidana_team.description')
                            "
                            :action-label="t('access.roles.kidana_team.action')"
                            :href="login()"
                            variant="neutral"
                        />
                    </div>

                    <div
                        class="mt-auto flex items-center justify-between gap-5 rounded-xl bg-marketing-teal/8 px-5 py-4 text-sm"
                    >
                        <span class="font-semibold text-marketing-teal-dark">{{
                            t('access.help.question')
                        }}</span>
                        <span class="shrink-0 font-bold text-marketing-teal">{{
                            t('access.help.action')
                        }}</span>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>
