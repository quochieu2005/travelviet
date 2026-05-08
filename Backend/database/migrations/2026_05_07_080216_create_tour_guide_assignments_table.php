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
        Schema::create('tour_guide_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_guide_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('tour_schedules');
            $table->date('assigned_date')->nullable();
            $table->string('role')->default('guide'); // guide, leader, support
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_guide_assignments');
    }
};
