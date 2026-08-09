<?php

namespace App\DTOs;

final readonly class OfficeDashboardData
{
    /**
     * @param  array{completionPercentage: int, deadlineAt: ?string, stationsCompleted: int, stationsTotal: int}  $workPlan
     * @param  array{pendingReportsCount: int, openObservationsCount: int, stationsCompletedFraction: string, seasonReadinessPercentage: int}  $stats
     * @param  array<int, array{track: string, percentage: int, byCity: array<int, array{city: string, percentage: int}>}>  $readinessTracks
     * @param  array<int, array{id: int, title: string, dueAt: string}>  $weeklyTasks
     * @param  array<int, array{id: int, event: string, createdAt: string}>  $recentActivity
     * @param  array{percentage: int}  $certificate
     */
    public function __construct(
        public string $officeName,
        public string $seasonName,
        public array $workPlan,
        public array $stats,
        public array $readinessTracks,
        public array $weeklyTasks,
        public array $recentActivity,
        public array $certificate,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'officeName' => $this->officeName,
            'seasonName' => $this->seasonName,
            'workPlan' => $this->workPlan,
            'stats' => $this->stats,
            'readinessTracks' => $this->readinessTracks,
            'weeklyTasks' => $this->weeklyTasks,
            'recentActivity' => $this->recentActivity,
            'certificate' => $this->certificate,
        ];
    }
}
