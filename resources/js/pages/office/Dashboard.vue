<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Award, CheckCircle2, ClipboardList, FileClock } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import ActivityFeedItem from '@/components/ActivityFeedItem.vue';
import ReadinessTrackBar from '@/components/ReadinessTrackBar.vue';
import StatCard from '@/components/StatCard.vue';
import TaskListItem from '@/components/TaskListItem.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'نظرة عامة', href: dashboard() }],
    },
});

const { t } = useI18n();

defineProps<{
    officeName: string;
    seasonName: string;
    workPlan: {
        completionPercentage: number;
        deadlineAt: string | null;
        stationsCompleted: number;
        stationsTotal: number;
    };
    stats: {
        pendingReportsCount: number;
        openObservationsCount: number;
        stationsCompletedFraction: string;
        seasonReadinessPercentage: number;
    };
    readinessTracks: {
        track: string;
        percentage: number;
        byCity: { city: string; percentage: number }[];
    }[];
    weeklyTasks: { id: number; title: string; dueAt: string }[];
    recentActivity: { id: number; event: string; createdAt: string }[];
    certificate: { percentage: number };
}>();

const trackLabels: Record<string, string> = {
    camps: 'مخيمات',
    housing: 'مساكن',
    catering: 'إعاشة',
    transport: 'نقل',
};
</script>

<template>
    <Head title="نظرة عامة" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <div>
            <h1 class="text-xl font-bold">
                {{ t('dashboard.welcome', { office: officeName }) }}
            </h1>
            <p class="text-sm text-muted-foreground">
                {{ t('dashboard.season', { season: seasonName }) }}
            </p>
        </div>

        <Card class="border-primary/30 bg-primary text-primary-foreground">
            <CardContent
                class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="space-y-2">
                    <div class="text-3xl font-bold">
                        {{ workPlan.completionPercentage }}%
                    </div>
                    <div class="font-medium">
                        {{ t('dashboard.work_plan.title') }}
                    </div>
                    <div class="text-sm opacity-90">
                        {{
                            t('dashboard.work_plan.stations_progress', {
                                completed: workPlan.stationsCompleted,
                                total: workPlan.stationsTotal,
                            })
                        }}
                    </div>
                    <Progress
                        :model-value="workPlan.completionPercentage"
                        class="w-64 bg-primary-foreground/20"
                    />
                </div>
                <Button variant="secondary">{{
                    t('dashboard.work_plan.cta')
                }}</Button>
            </CardContent>
        </Card>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <StatCard
                :icon="FileClock"
                :value="stats.pendingReportsCount"
                :label="t('dashboard.stats.pending_reports')"
            />
            <StatCard
                :icon="ClipboardList"
                :value="stats.openObservationsCount"
                :label="t('dashboard.stats.open_observations')"
            />
            <StatCard
                :icon="CheckCircle2"
                :value="stats.stationsCompletedFraction"
                :label="t('dashboard.stats.stations_completed')"
            />
            <StatCard
                :icon="Award"
                :value="`${stats.seasonReadinessPercentage}%`"
                :label="t('dashboard.stats.season_readiness')"
            />
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <Card>
                <CardHeader>
                    <CardTitle>{{
                        t('dashboard.weekly_tasks.title')
                    }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <ul
                        v-if="weeklyTasks.length"
                        class="divide-y divide-border"
                    >
                        <TaskListItem
                            v-for="task in weeklyTasks"
                            :key="task.id"
                            :title="task.title"
                            :due-at="task.dueAt"
                        />
                    </ul>
                    <p v-else class="text-sm text-muted-foreground">
                        {{ t('dashboard.weekly_tasks.empty') }}
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>{{ t('dashboard.readiness.title') }}</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <ReadinessTrackBar
                        v-for="item in readinessTracks"
                        :key="item.track"
                        :label="trackLabels[item.track] ?? item.track"
                        :percentage="item.percentage"
                        :by-city="item.byCity"
                    />
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <Card>
                <CardHeader>
                    <CardTitle>{{ t('dashboard.activity.title') }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <ul
                        v-if="recentActivity.length"
                        class="divide-y divide-border"
                    >
                        <ActivityFeedItem
                            v-for="item in recentActivity"
                            :key="item.id"
                            :event="item.event"
                            :created-at="item.createdAt"
                        />
                    </ul>
                    <p v-else class="text-sm text-muted-foreground">
                        {{ t('dashboard.activity.empty') }}
                    </p>
                </CardContent>
            </Card>

            <Card class="bg-muted/40">
                <CardHeader>
                    <CardTitle>{{
                        t('dashboard.certificate.title')
                    }}</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3">
                    <p class="text-sm text-muted-foreground">
                        {{ t('dashboard.certificate.description') }}
                    </p>
                    <Progress :model-value="certificate.percentage" />
                    <Link
                        href="#"
                        class="inline-flex h-9 items-center justify-center rounded-md border border-border px-4 text-sm font-medium hover:bg-accent hover:text-accent-foreground"
                    >
                        {{ t('dashboard.certificate.cta') }}
                    </Link>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
