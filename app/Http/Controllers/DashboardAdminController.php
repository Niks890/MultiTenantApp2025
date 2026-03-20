<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalTenants = DB::table('t_tenants')->count();

        $newTenantsThisMonth = DB::table('t_tenants')
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalUsers = DB::table('system_users')->count();

        $adminTenants = DB::table('m_admin_tenants')->count();
        $newAdminTenantsThisMonth = DB::table('m_admin_tenants')
            ->whereMonth('created_at', now()->month)
            ->count();


        $activeUsers = DB::table('system_users')
            ->where('last_login_date', '>=', now()->subDays(7))
            ->count();

        $revenue = DB::table('t_transactions')->sum('amount');
        $topTenants = DB::table('t_tenants as tt')
            ->leftJoin('t_contracts as tc', 'tc.tenant_id', '=', 'tt.id')
            ->leftJoin('t_transactions as tr', 'tr.contract_id', '=', 'tc.id')
            ->leftJoin('m_admin_tenants as ta', 'ta.id', '=', 'tt.admin_tenant_id')
            ->select(
                'tt.name',
                'tt.is_active',
                'tt.maintenance_mode',
                DB::raw('1 as users'),
                DB::raw('COALESCE(SUM(tr.amount),0) as revenue'),
                'ta.display_name as admin_name'
            )
            ->groupBy('tt.id', 'tt.name', 'tt.is_active', 'tt.maintenance_mode', 'ta.display_name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();


            $activeTenants = DB::table('t_tenants')
                ->where('is_active', 1)
                ->where('maintenance_mode', null)
                ->count();

            $suspendedTenants = DB::table('t_tenants')
                ->where('is_active', 0)
                ->whereNotNull('maintenance_mode')
                ->count();

            $expiredTenants = DB::table('t_tenants')
                ->where('is_active', 0)
                ->where('delete_flg', 1)
                ->count();

        return view('admin.dashboard.index', compact(
            'totalTenants',
            'newTenantsThisMonth',
            'totalUsers',
            'activeUsers',
            'revenue',
            'topTenants',
            'activeTenants',
            'suspendedTenants',
            'expiredTenants',
            'adminTenants',
            'newAdminTenantsThisMonth'
        ));
    }
}
