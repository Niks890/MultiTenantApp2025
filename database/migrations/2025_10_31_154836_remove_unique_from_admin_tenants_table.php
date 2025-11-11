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
        Schema::table('m_admin_tenants', function (Blueprint $table) {
            $table->dropUnique('admin_tenants_phone_number_unique');
            $table->dropUnique('admin_tenants_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_admin_tenants', function (Blueprint $table) {
            $table->unique('phone_number', 'admin_tenants_phone_number_unique');
            $table->unique('email', 'admin_tenants_email_unique');
        });
    }
};
