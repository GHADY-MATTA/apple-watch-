<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklyInsight;

class WeeklyInsightsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $insights = WeeklyInsight::where('user_id', $userId)->latest()->get();

        return response()->json(['insights' => $insights]);
    }
}
