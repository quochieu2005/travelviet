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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->unique()->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price_adult', 12, 2)->nullable();
            $table->decimal('price_child', 12, 2)->nullable();
            $table->integer('price_discount_percent')->default(0);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->integer('availability')->default(0);
            $table->text('itinerary')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_people')->nullable();
            $table->integer('duration_days')->nullable();
            $table->string('departure_location', 255)->nullable();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->integer('views')->default(0);
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('included_services')->nullable();
            $table->json('excluded_services')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
