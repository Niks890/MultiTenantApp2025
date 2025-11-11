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
        Schema::create('tenants', function (Blueprint $table) {
            // $table->id();
            $table->string('id')->primary();
            $table->string('name', 100);
            $table->string('access_key');
            $table->string('hash_code')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('db_connection', 100)->default('mysql');
            $table->string('db_host')->default('127.0.0.1');
            $table->integer('db_port')->default('3306');
            $table->string('db_name', 100);
            $table->string('db_username', 100);
            $table->string('db_password');
            $table->timestamps();
            $table->boolean('delete_flg')->default(false);
            $table->foreignId('admin_tenant_id')->constrained();
            $table->foreignId('group_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
