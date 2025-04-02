<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyInsight;
use Illuminate\Support\Facades\Auth;

class MonthlyInsightController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $insights = MonthlyInsight::where('user_id', $user->id)->latest()->get();

        return response()->json([
            'insights' => $insights
        ]);
    }
}
