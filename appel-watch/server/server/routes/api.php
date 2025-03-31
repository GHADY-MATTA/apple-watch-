<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthDataController;




// use Illuminate\Support\Facades\Route;
use App\Services\HealthAIService;

Route::post('/test-ai', function (\Illuminate\Http\Request $request, HealthAIService $aiService) {
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



Route::post('/upload-csv', action: [HealthDataController::class, 'upload']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


