<?php

namespace App\Http\Controllers;

use App\Services\HealthCSVService;
use App\Events\HealthDataUploaded;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HealthDataController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('📩 Received upload request.');

        // 👤 TEMP: hardcoded user for now
        $userId = 1;

        // 📁 Get file from request
        $file = $request->file('file');

        // 🧪 Debug logs
        Log::info('🔍 All request keys: ' . json_encode($request->all()));
        Log::info('🔍 Files array: ' . json_encode($request->allFiles()));
        Log::info("📦 hasFile('file')? " . var_export($request->hasFile('file'), true));

        // ❌ File not found
        if (!$file) {
            Log::error('🚫 No file was uploaded.');
            return response()->json(['error' => 'No file uploaded.'], 400);
        }

        // ✅ Use real path of uploaded file (avoid Laravel storage bug on Windows)
        $fullPath = $file->getRealPath();

        Log::info("📁 Real file path: $fullPath");
        Log::info("📁 File exists? " . (file_exists($fullPath) ? 'yes' : 'no'));

        // 🔁 Generate unique upload ID for traceability
        $batchId = Str::uuid();
        Log::info("🆔 Generated batch ID: $batchId");

        // 🧠 Parse and store data
        try {
            Log::info("🧪 Calling HealthCSVService...");
            $count = app(HealthCSVService::class)->parseAndStore(
                $fullPath,
                $userId,
                $batchId
            );
        } catch (\Throwable $e) {
            Log::error('❌ CSV parsing failed: ' . $e->getMessage());
            return response()->json(['error' => 'CSV parsing failed'], 500);
        }

        // 🚀 Fire event for AI analysis
        event(new HealthDataUploaded($userId, $batchId));
        Log::info("📡 HealthDataUploaded event fired.");

        // ✅ Respond with result
        return response()->json([
            'message' => '✅ CSV uploaded and processed.',
            'rows_saved' => $count,
        ]);
    }
}
