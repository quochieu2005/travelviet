<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 12, 2);
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('restrict');
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('reviews')->default(0);
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_id')->nullable();
            $table->json('facilities')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};