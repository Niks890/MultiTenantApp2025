<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTenantActive
{
    public function handle(Request $request, Closure $next)
    {
        if (function_exists('tenant') && tenant()) {
            $tenant = tenant();
            if ($tenant->delete_flg) {
                abort(404);
            }else if($tenant->maintenance_mode != null){
                abort(503);
            }
        }
        return $next($request);
    }
}
