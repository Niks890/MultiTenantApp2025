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
            $table->json('maintenance_mode')->nullable()->after('is_active');
            $table->json('data')->nullable()->after('maintenance_mode');
            $table->renameColumn('db_connection', 'tenancy_db_connection');
            $table->renameColumn('db_host', 'tenancy_db_host');
            $table->renameColumn('db_port', 'tenancy_db_port');
            $table->renameColumn('db_name', 'tenancy_db_name');
            $table->renameColumn('db_username', 'tenancy_db_username');
            $table->renameColumn('db_password', 'tenancy_db_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->dropColumn(['maintenance_mode', 'data']);
            $table->renameColumn('tenancy_db_connection', 'db_connection');
            $table->renameColumn('tenancy_db_host', 'db_host');
            $table->renameColumn('tenancy_db_port', 'db_port');
            $table->renameColumn('tenancy_db_name', 'db_name');
            $table->renameColumn('tenancy_db_username', 'db_username');
            $table->renameColumn('tenancy_db_password', 'db_password');
        });
    }
};
