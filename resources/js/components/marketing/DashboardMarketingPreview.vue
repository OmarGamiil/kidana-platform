<script setup lang="ts">
import { BarChart3, Bell, ClipboardList, Home, Settings } from '@lucide/vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const progressRows = [
    { key: 'camps', width: '88%', color: 'bg-marketing-teal' },
    { key: 'housing', width: '76%', color: 'bg-marketing-gold' },
    { key: 'catering', width: '69%', color: 'bg-marketing-teal' },
    { key: 'transport', width: '61%', color: 'bg-marketing-gold' },
];
</script>

<template>
    <div
        class="overflow-hidden rounded-2xl border border-marketing-border bg-white shadow-lg shadow-marketing-teal-dark/10"
    >
        <div class="grid min-h-[28rem] grid-cols-[5.5rem_1fr]">
            <aside class="bg-marketing-teal-dark px-3 py-6 text-white">
                <div class="mx-auto size-9 rounded-lg bg-white/90 p-1.5">
                    <img
                        src="/images/kidana-logo.jpg"
                        alt=""
                        class="size-full object-contain mix-blend-multiply"
                    />
                </div>
                <div class="mt-7 grid gap-3">
                    <span
                        v-for="icon in [
                            Home,
                            BarChart3,
                            ClipboardList,
                            Bell,
                            Settings,
                        ]"
                        :key="icon.name"
                        class="flex h-10 items-center justify-center rounded-lg text-white/55 first:bg-marketing-teal first:text-white"
                    >
                        <component :is="icon" class="size-4" />
                    </span>
                </div>
            </aside>
            <div class="bg-marketing-surface p-5 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-marketing-muted">
                            {{ t('marketing.dashboard.preview.season') }}
                        </p>
                        <h3 class="mt-1 font-bold text-marketing-teal-dark">
                            {{ t('marketing.dashboard.preview.title') }}
                        </h3>
                    </div>
                    <span
                        class="rounded-full bg-marketing-gold-soft px-3 py-1 text-[10px] font-semibold text-marketing-teal-dark"
                        >{{ t('marketing.dashboard.preview.status') }}</span
                    >
                </div>
                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div class="rounded-xl bg-white p-4">
                        <strong class="text-xl text-marketing-teal">82%</strong>
                        <p class="mt-1 text-[10px] text-marketing-muted">
                            {{
                                t(
                                    'marketing.dashboard.preview.metrics.readiness',
                                )
                            }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-white p-4">
                        <strong class="text-xl text-marketing-teal">24</strong>
                        <p class="mt-1 text-[10px] text-marketing-muted">
                            {{
                                t(
                                    'marketing.dashboard.preview.metrics.completed',
                                )
                            }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-white p-4">
                        <strong class="text-xl text-marketing-gold">36</strong>
                        <p class="mt-1 text-[10px] text-marketing-muted">
                            {{
                                t('marketing.dashboard.preview.metrics.reports')
                            }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 rounded-xl bg-white p-5">
                    <h4 class="text-xs font-bold text-marketing-teal-dark">
                        {{ t('marketing.dashboard.preview.chart') }}
                    </h4>
                    <div class="mt-4 grid gap-3">
                        <div
                            v-for="row in progressRows"
                            :key="row.key"
                            class="grid grid-cols-[4rem_1fr] items-center gap-3"
                        >
                            <span class="text-[9px] text-marketing-muted">{{
                                t(
                                    `marketing.dashboard.preview.tracks.${row.key}`,
                                )
                            }}</span>
                            <div class="h-1.5 rounded-full bg-marketing-border">
                                <div
                                    :class="['h-full rounded-full', row.color]"
                                    :style="{ width: row.width }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 rounded-xl bg-white p-5">
                    <h4 class="text-xs font-bold text-marketing-teal-dark">
                        {{ t('marketing.dashboard.preview.activity') }}
                    </h4>
                    <div class="mt-3 grid gap-2">
                        <div
                            v-for="status in [
                                'reviewed',
                                'pending',
                                'complete',
                            ]"
                            :key="status"
                            class="flex items-center justify-between border-b border-marketing-border pb-2 text-[9px] last:border-0 last:pb-0"
                        >
                            <span class="text-marketing-muted">{{
                                t(
                                    `marketing.dashboard.preview.activities.${status}`,
                                )
                            }}</span>
                            <span
                                class="rounded-full bg-marketing-cream px-2 py-1 text-marketing-teal"
                                >{{
                                    t(
                                        `marketing.dashboard.preview.activity_status.${status}`,
                                    )
                                }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
