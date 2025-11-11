<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->string('site_name', 150)->nullable()->after('name');
            $table->string('logo')->nullable()->after('site_name');
            $table->string('address', 255)->nullable()->after('logo');
            $table->string('facebook_url')->nullable()->after('address');
            $table->string('tiktok_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('tiktok_url');
        });
    }

    public function down(): void
    {
        Schema::table('t_tenants', function (Blueprint $table) {
            $table->dropColumn([
                'site_name',
                'logo',
                'address',
                'facebook_url',
                'tiktok_url',
                'instagram_url',
            ]);
        });
    }
};
