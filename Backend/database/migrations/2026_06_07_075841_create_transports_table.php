<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->foreignId('id_destination')
                ->constrained('destinations')
                ->cascadeOnDelete();

            $table->string('mileage')->nullable();
            $table->string('transmission')->nullable();

            $table->integer('trips')->default(0);
            $table->integer('seats')->default(0);

            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review')->default(0);

            $table->decimal('price', 12, 2)->default(0);

            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};