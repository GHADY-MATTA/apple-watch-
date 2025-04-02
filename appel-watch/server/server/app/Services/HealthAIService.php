<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

ini_set('max_execution_time', 180); // 3 minutes

class HealthAIService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key'); // This must be defined in config/services.php
        $this->model = 'gemini-2.0-flash';
    }

    public function analyze(string $prompt): ?string
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        Log::info("📡 Sending prompt to Gemini", ['prompt' => $prompt]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            $data = $response->json();

            Log::info("✅ Gemini raw response", ['response' => $data]);

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (\Exception $e) {
            Log::error("❌ Gemini API call failed", ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function generateInsights(array $data, string $period): ?array
    {
        $prompt = <<<EOT
You are a health and fitness analytics engine. Analyze the following {$period} Apple Watch activity data for a single user.

Your ONLY job is to return predictive analytics and actionable insights, formatted as a strict JSON object using EXACTLY the field names and structure below.

DO NOT:
- Add extra keys
- Rename or invent new sections
- Return summaries, comments, or explanations

Your response must match this structure exactly:

{
  "predictive_analytics": {
    "average_steps": <float>,
    "average_distance_km": <float>,
    "average_active_minutes": <float>,
    "step_increase_probability": <float>,
    "distance_increase_probability": <float>,
    "active_minutes_increase_probability": <float>
  },
  "actionable_insights": {
    "focus_area": "<string>",
    "recommendations": [
      "<string>",
      "<string>",
      "<string>"
    ]
  }
}

INPUT DATA:

EOT;

        $prompt .= json_encode($data, JSON_PRETTY_PRINT);

        $responseText = $this->analyze($prompt);

        if ($responseText) {
            // ✅ Clean response from Gemini: strip ```json ... ``` wrapper if present
            $cleaned = preg_replace('/^```json\s*|\s*```$/', '', trim($responseText));

            $decoded = json_decode($cleaned, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("❌ JSON decode error", ['error' => json_last_error_msg(), 'raw' => $cleaned]);
                return null;
            }

            return $decoded;
        }

        return null;
    }
}
