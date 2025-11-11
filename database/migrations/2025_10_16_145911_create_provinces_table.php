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
        Schema::create('m_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('province_code')->unique();
            $table->string('name')->index();
            $table->string('short_name');
            $table->string('code');
            $table->string('place_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_provinces');
    }
};
