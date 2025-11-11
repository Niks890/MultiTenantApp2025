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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->date('start_at');
            $table->date('end_at');
            $table->decimal('amount_before_tax', 10, 2)->default(0);
            $table->decimal('amount_after_tax', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->date('due_date');
            $table->boolean('payment_mode')->default(false);
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->boolean('delete_flg')->default(false);
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('tax_id')->constrained();
            // $table->foreignId('tenant_id')->constrained();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
