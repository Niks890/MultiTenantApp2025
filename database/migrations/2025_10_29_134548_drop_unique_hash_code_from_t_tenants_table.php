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
        Schema::table('t_tenants', function (Blueprint $table) {
           $table->dropUnique('tenants_hash_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->unique('hash_code', 'tenants_hash_code_unique');
        });
    }
};
