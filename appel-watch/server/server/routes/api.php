






<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthDataController;
use App\Services\HealthAIService;
use App\Http\Controllers\WeeklyInsightsController;
use App\Http\Controllers\MonthlyInsightController;

// ================== AUTH ROUTES ==================

Route::middleware('auth:api')->get('/cycle-insights', [\App\Http\Controllers\CycleInsightController::class, 'index']);

Route::middleware('auth:api')->get('/monthly-insights', [MonthlyInsightController::class, 'index']);

Route::middleware('auth:api')->get('/weekly-insights', [WeeklyInsightsController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Routes that require authentication (using Passport)
Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });

    // Upload health CSV file
    Route::post('/upload-csv', [HealthDataController::class, 'upload']);

    // Optional secured route to test AI
    Route::post('/test-ai', function (Request $request, HealthAIService $aiService) {
        $prompt = $request->input('prompt');

        if (!$prompt) {
            return response()->json(['error' => 'Prompt is required.'], 400);
        }

        $response = $aiService->analyze($prompt);

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    });
});

// ========== REMOVE THIS IF NOT USING SANCTUM ==========
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


































// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HealthDataController;




// use Illuminate\Support\Facades\Route;
// use App\Services\HealthAIService;




// use App\Http\Controllers\AuthController;

// Route::middleware('auth:api')->get('/me', function (Request $request) {
//     return response()->json($request->user());
// });


// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);



// Route::post('/test-ai', function (\Illuminate\Http\Request $request, HealthAIService $aiService) {
//     $prompt = $request->input('prompt');

//     if (!$prompt) {
//         return response()->json(['error' => 'Prompt is required.'], 400);
//     }

//     $response = $aiService->analyze($prompt);

//     return response()->json([
//         'success' => true,
//         'data' => $response
//     ]);
// });



// Route::post('/upload-csv', action: [HealthDataController::class, 'upload']);


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
