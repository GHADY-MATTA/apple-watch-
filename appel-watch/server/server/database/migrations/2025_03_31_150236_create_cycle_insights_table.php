<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cycle_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Predictive Analytics
            $table->float('average_steps');
            $table->float('average_distance_km');
            $table->float('average_active_minutes');
            $table->float('step_increase_probability');
            $table->float('distance_increase_probability');
            $table->float('active_minutes_increase_probability');

            // Actionable Insights
            $table->string('focus_area');
            $table->json('recommendations');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycle_insights');
    }
};
