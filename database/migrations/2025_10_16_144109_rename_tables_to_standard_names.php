<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('groups', 'm_groups');
        Schema::rename('admin_tenants', 'm_admin_tenants');
        Schema::rename('tenants', 't_tenants');
        Schema::rename('domains', 'm_domains');
        Schema::rename('plans', 'm_plans');
        Schema::rename('payment_methods', 'm_payment_methods');
        Schema::rename('taxes', 'm_taxes');
        Schema::rename('contracts', 't_contracts');
        Schema::rename('transactions', 't_transactions');
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('m_groups', 'groups');
        Schema::rename('m_admin_tenants', 'admin_tenants');
        Schema::rename('t_tenants', 'tenants');
        Schema::rename('m_domains', 'domains');
        Schema::rename('m_plans', 'plans');
        Schema::rename('m_payment_methods', 'payment_methods');
        Schema::rename('m_taxes', 'taxes');
        Schema::rename('t_contracts', 'contracts');
        Schema::rename('t_transactions', 'transactions');
    }
};
