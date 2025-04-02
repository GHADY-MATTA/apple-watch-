<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CycleInsight;
use Illuminate\Support\Facades\Auth;

class CycleInsightController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $insights = CycleInsight::where('user_id', $user->id)->latest()->get();

        return response()->json([
            'insights' => $insights
        ]);
    }
}
