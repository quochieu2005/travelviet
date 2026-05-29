<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_schedules', function (Blueprint $table) {
            $table->decimal('price_override_child', 12, 2)->nullable()->after('price_override');
            $table->string('note')->nullable()->after('price_override_child');
        });
    }

    public function down(): void
    {
        Schema::table('tour_schedules', function (Blueprint $table) {
            $table->dropColumn(['price_override_child', 'note']);
        });
    }
};