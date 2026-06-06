<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_inquiries', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'contacted',
                'closed',      // giữ lại nếu cần
                'completed',   // thêm
                'cancelled'
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pricing_inquiries', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'contacted',
                'closed'
            ])->default('pending')->change();
        });
    }
};