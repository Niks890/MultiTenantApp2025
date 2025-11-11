<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $cpuUsage = function_exists('sys_getloadavg') ? sys_getloadavg()[0] : 45;
        if (file_exists('/proc/meminfo')) {
            $memInfo = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+) kB/', $memInfo, $totalMatch);
            preg_match('/MemAvailable:\s+(\d+) kB/', $memInfo, $availMatch);
            $memoryTotal = $totalMatch[1] ?? 1;
            $memoryAvailable = $availMatch[1] ?? 0;
            $memoryUsage = round((($memoryTotal - $memoryAvailable) / $memoryTotal) * 100);
        } else {
            $memoryUsage = 62;
        }
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsage = round((($diskTotal - $diskFree) / $diskTotal) * 100);
        try {
            DB::connection()->getPdo();
            $dbHealth = 100;
        } catch (\Exception $e) {
            $dbHealth = 0;
        }
        return view('admin.dashboard.index', compact(
            'cpuUsage',
            'memoryUsage',
            'diskUsage',
            'dbHealth'
        ));
    }
}
