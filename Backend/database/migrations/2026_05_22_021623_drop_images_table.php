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
        Schema::dropIfExists('images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tour_id')
                ->constrained('tours')
                ->onDelete('cascade');

            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_main')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }
};