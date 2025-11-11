<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class TenantLogger
{
  public function __invoke(array $config)
    {
        $tenantId = $config['tenant_id'] ?? 'unknown';
        $tenantName = $config['tenant_name'] ?? 'tenant';
        $slugName = Str::slug($tenantName, '_');
        $tenantIdentifier = "{$tenantId}_{$slugName}";
        $logDir = storage_path("logs/tenants/{$tenantIdentifier}");
        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }
        $logPath = "{$logDir}/system.log";
        $logger = new Logger("tenant_{$tenantIdentifier}");
        $output = "[%datetime%] %channel%.%level_name%: %message% %context%\n";
        $formatter = new LineFormatter($output, 'Y-m-d H:i:s', true, true);
        $handler = new StreamHandler($logPath, Logger::INFO);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        return $logger;
    }
}
