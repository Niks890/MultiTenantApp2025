<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m_payment_methods', function (Blueprint $table) {
            $table->dropUnique(['name', 'delete_flg']);
            $table->unique(['name', 'delete_flg', 'id'], 'payment_methods_unique');
        });
    }

    public function down(): void
    {
        Schema::table('m_payment_methods', function (Blueprint $table) {
            $table->dropUnique('payment_methods_unique');
            $table->unique(['name', 'delete_flg']);
        });
    }
};
