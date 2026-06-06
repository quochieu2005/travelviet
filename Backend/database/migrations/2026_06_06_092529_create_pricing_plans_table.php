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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('description', 255)->nullable();

            $table->decimal('price', 15, 0)->nullable();
            $table->string('price_note')->nullable();

            $table->json('features')->nullable();
            $table->json('disabled_features')->nullable();

            $table->boolean('is_popular')->default(false);

            $table->string('button_text', 50)->default('Try Now');

            $table->integer('order')->default(0);

            $table->string('status', 20)->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};