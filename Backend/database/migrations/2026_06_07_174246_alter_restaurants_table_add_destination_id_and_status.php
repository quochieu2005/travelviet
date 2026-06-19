<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {

            // Xóa cột location
            $table->dropColumn('location');

            // Thêm destination_id
            $table->foreignId('destination_id')
                  ->nullable()
                  ->constrained('destinations')
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {

            $table->dropForeign(['destination_id']);
            $table->dropColumn(['destination_id']);

            $table->string('location');
        });
    }
};