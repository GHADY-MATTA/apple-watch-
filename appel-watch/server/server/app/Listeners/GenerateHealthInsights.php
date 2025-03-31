<?php

namespace App\Listeners;

use App\Events\HealthDataUploaded;
use App\Models\HealthMetric;
use App\Services\HealthAIService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\WeeklyInsight;
use App\Models\MonthlyInsight;
use App\Models\CycleInsight;

class GenerateHealthInsights
{
    /**
     * Handle the event.
     */
    public function handle(HealthDataUploaded $event): void
    {
        Log::info("🧠 Generating insights for user_id: {$event->userId}");

        $endDate = Carbon::now();

        $weeklyData = HealthMetric::where('user_id', $event->userId)
            ->whereBetween('date', [$endDate->copy()->subDays(6)->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->get(['date', 'steps', 'distance_km', 'active_minutes'])
            ->toArray();

        $monthlyData = HealthMetric::where('user_id', $event->userId)
            ->whereBetween('date', [$endDate->copy()->subDays(29)->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->get(['date', 'steps', 'distance_km', 'active_minutes'])
            ->toArray();

        $cycleData = HealthMetric::where('user_id', $event->userId)
            ->whereBetween('date', [$endDate->copy()->subDays(89)->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->get(['date', 'steps', 'distance_km', 'active_minutes'])
            ->toArray();

        // Call AI Service
        $aiService = new HealthAIService();

        $weeklyInsights = $aiService->generateInsights($weeklyData, 'weekly');
        $monthlyInsights = $aiService->generateInsights($monthlyData, 'monthly');
        $cycleInsights = $aiService->generateInsights($cycleData, 'cycle');

        // Logging
        Log::info("📈 Weekly AI insights:", ['data' => $weeklyInsights]);
        Log::info("🗓️ Monthly AI insights:", ['data' => $monthlyInsights]);
        Log::info("🔄 Cycle AI insights:", ['data' => $cycleInsights]);

        // Save to DB
        if ($weeklyInsights) {
            WeeklyInsight::create([
                'user_id' => $event->userId,
                'average_steps' => $weeklyInsights['predictive_analytics']['average_steps'] ?? null,
                'average_distance_km' => $weeklyInsights['predictive_analytics']['average_distance_km'] ?? null,
                'average_active_minutes' => $weeklyInsights['predictive_analytics']['average_active_minutes'] ?? null,
                'step_increase_probability' => $weeklyInsights['predictive_analytics']['step_increase_probability'] ?? null,
                'distance_increase_probability' => $weeklyInsights['predictive_analytics']['distance_increase_probability'] ?? null,
                'active_minutes_increase_probability' => $weeklyInsights['predictive_analytics']['active_minutes_increase_probability'] ?? null,
                'focus_area' => $weeklyInsights['actionable_insights']['focus_area'] ?? null,
                'recommendations' => $weeklyInsights['actionable_insights']['recommendations'] ?? [],
                'raw_json' => $weeklyInsights,
            ]);
        }

        if ($monthlyInsights) {
            MonthlyInsight::create([
                'user_id' => $event->userId,
                'average_steps' => $monthlyInsights['predictive_analytics']['average_steps'] ?? null,
                'average_distance_km' => $monthlyInsights['predictive_analytics']['average_distance_km'] ?? null,
                'average_active_minutes' => $monthlyInsights['predictive_analytics']['average_active_minutes'] ?? null,
                'step_increase_probability' => $monthlyInsights['predictive_analytics']['step_increase_probability'] ?? null,
                'distance_increase_probability' => $monthlyInsights['predictive_analytics']['distance_increase_probability'] ?? null,
                'active_minutes_increase_probability' => $monthlyInsights['predictive_analytics']['active_minutes_increase_probability'] ?? null,
                'focus_area' => $monthlyInsights['actionable_insights']['focus_area'] ?? null,
                'recommendations' => $monthlyInsights['actionable_insights']['recommendations'] ?? [],
                'raw_json' => $monthlyInsights,
            ]);
        }

        if ($cycleInsights) {
            CycleInsight::create([
                'user_id' => $event->userId,
                'average_steps' => $cycleInsights['predictive_analytics']['average_steps'] ?? null,
                'average_distance_km' => $cycleInsights['predictive_analytics']['average_distance_km'] ?? null,
                'average_active_minutes' => $cycleInsights['predictive_analytics']['average_active_minutes'] ?? null,
                'step_increase_probability' => $cycleInsights['predictive_analytics']['step_increase_probability'] ?? null,
                'distance_increase_probability' => $cycleInsights['predictive_analytics']['distance_increase_probability'] ?? null,
                'active_minutes_increase_probability' => $cycleInsights['predictive_analytics']['active_minutes_increase_probability'] ?? null,
                'focus_area' => $cycleInsights['actionable_insights']['focus_area'] ?? null,
                'recommendations' => $cycleInsights['actionable_insights']['recommendations'] ?? [],
                'raw_json' => $cycleInsights,
            ]);
        }
    }
}
