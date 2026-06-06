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
        Schema::create('pricing_inquiries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pricing_plan_id')
                ->constrained('pricing_plans')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('phone', 20);

            $table->text('message')->nullable();

            $table->enum('status', [
                'pending',
                'contacted',
                'closed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_inquiries');
    }
};