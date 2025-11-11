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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2)->unsigned();
            $table->date('payment_date');
            $table->string('file_path', 255)->nullable();
            $table->timestamps();
            $table->boolean('delete_flg')->default(false);
            $table->foreignId('payment_method_id')->constrained();
            $table->foreignId('contract_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
