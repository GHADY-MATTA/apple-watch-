<?php

namespace App\Services;

use App\Models\HealthMetric;
use Illuminate\Support\Facades\Log;

class HealthCSVService
{
    public function parseAndStore(string $filePath, int $userId, string $batchId): int
    {
        Log::info("\uD83D\uDCC5 Service started for file: $filePath");

        if (!file_exists($filePath)) {
            Log::error("\u274C File does not exist: $filePath");
            throw new \Exception("Uploaded file not found.");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            Log::error("\u274C Failed to open file.");
            throw new \Exception("Could not open file.");
        }

        Log::info("\u2705 File opened successfully.");

        $header = fgetcsv($handle);
        Log::info("\uD83D\uDCC4 Parsed header: ", $header);

        $expected = ['date', 'steps', 'distance_km', 'active_minutes'];
        if ($header !== $expected) {
            Log::error("\u274C Invalid CSV format. Expected: " . implode(', ', $expected));
            throw new \Exception("Invalid CSV format.");
        }

        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== 4) {
                Log::warning("\u26A0\uFE0F Skipping invalid row: " . json_encode($row));
                continue;
            }

            [$date, $steps, $distance, $minutes] = $row;

            Log::info('Parsed row', [
                'user_id' => $userId,
                'date' => $date,
                'steps' => $steps,
                'distance_km' => $distance,
                'active_minutes' => $minutes,
                'batch_id' => $batchId,
            ]);

            HealthMetric::updateOrCreate([
                'user_id' => $userId,
                'date' => $date,
            ], [
                'steps' => (int) $steps,
                'distance_km' => (float) $distance,
                'active_minutes' => (int) $minutes,
                'upload_batch_id' => $batchId,
            ]);

            $count++;
        }

        fclose($handle);
        Log::info("\uD83D\uDCCA CSV processed: $count rows saved for user_id $userId");

        return $count;
    }
}
