<?php

namespace App\Services;

use App\DTOs\OfficeDashboardData;
use App\Enums\ItemStatus;
use App\Enums\ReportStatus;
use App\Models\AuditLogEntry;
use App\Models\Observation;
use App\Models\Report;
use App\Models\Station;
use App\Models\WorkPlan;
use App\Support\CurrentOfficeContext;
use Illuminate\Support\Collection;

/**
 * Single use-case: assemble everything the office Dashboard needs to render.
 * Always scoped through the CALLER-supplied CurrentOfficeContext (which is
 * itself resolved server-side from Auth::user()) — never from a client-
 * supplied id — so cross-office data isolation holds by construction.
 */
final class OfficeDashboardService
{
    public function build(CurrentOfficeContext $context): OfficeDashboardData
    {
        $officeSeasonId = $context->officeSeason->id;

        $stations = Station::query()
            ->where('office_season_id', $officeSeasonId)
            ->with('city')
            ->get();

        $stationsTotal = $stations->count();
        $stationsCompleted = $stations->where('is_completed', true)->count();
        $completionPercentage = $this->percentage($stationsCompleted, $stationsTotal);

        $workPlan = WorkPlan::query()->where('office_season_id', $officeSeasonId)->first();

        $pendingReportsCount = Report::query()
            ->where('office_season_id', $officeSeasonId)
            ->where('status', ReportStatus::Pending)
            ->count();

        $openObservationsCount = Observation::query()
            ->where('office_season_id', $officeSeasonId)
            ->where('status', ItemStatus::Open)
            ->count();

        return new OfficeDashboardData(
            officeName: $context->office->name,
            seasonName: $context->season->name,
            workPlan: [
                'completionPercentage' => $completionPercentage,
                'deadlineAt' => $workPlan?->deadline_at?->toDateString(),
                'stationsCompleted' => $stationsCompleted,
                'stationsTotal' => $stationsTotal,
            ],
            stats: [
                'pendingReportsCount' => $pendingReportsCount,
                'openObservationsCount' => $openObservationsCount,
                'stationsCompletedFraction' => "{$stationsCompleted}/{$stationsTotal}",
                // Provisional — same base as work-plan %, pending product
                // confirmation of the real formula (see plan §0 decision #5).
                'seasonReadinessPercentage' => $completionPercentage,
            ],
            readinessTracks: $this->readinessTracks($stations),
            weeklyTasks: $this->weeklyTasks($stations),
            recentActivity: $this->recentActivity($officeSeasonId),
            certificate: ['percentage' => $completionPercentage],
        );
    }

    /**
     * @param  Collection<int, Station>  $stations
     * @return array<int, array{track: string, percentage: int, byCity: array<int, array{city: string, percentage: int}>}>
     */
    private function readinessTracks($stations): array
    {
        return $stations
            ->groupBy(fn (Station $station): string => $station->track->value)
            ->map(function (Collection $trackStations, string $track): array {
                $byCity = $trackStations
                    ->groupBy(fn (Station $station): string => $station->city->name_ar)
                    ->map(fn (Collection $cityStations, string $cityName): array => [
                        'city' => $cityName,
                        'percentage' => $this->percentage(
                            $cityStations->where('is_completed', true)->count(),
                            $cityStations->count(),
                        ),
                    ])
                    ->values()
                    ->all();

                return [
                    'track' => $track,
                    'percentage' => $this->percentage(
                        $trackStations->where('is_completed', true)->count(),
                        $trackStations->count(),
                    ),
                    'byCity' => $byCity,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, Station>  $stations
     * @return array<int, array{id: int, title: string, dueAt: string}>
     */
    private function weeklyTasks($stations): array
    {
        return $stations
            ->where('is_completed', false)
            ->whereNotNull('due_at')
            ->filter(fn (Station $station) => $station->due_at->lte(now()->addDays(7)))
            ->sortBy('due_at')
            ->map(fn (Station $station) => [
                'id' => $station->id,
                'title' => $station->name,
                'dueAt' => $station->due_at->toDateString(),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{id: int, event: string, createdAt: string}>
     */
    private function recentActivity(int $officeSeasonId): array
    {
        return AuditLogEntry::query()
            ->where('office_season_id', $officeSeasonId)
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn (AuditLogEntry $entry) => [
                'id' => $entry->id,
                'event' => $entry->event->value,
                'createdAt' => $entry->created_at->toIso8601String(),
            ])
            ->all();
    }

    private function percentage(int $part, int $total): int
    {
        return $total > 0 ? (int) round($part / $total * 100) : 0;
    }
}
