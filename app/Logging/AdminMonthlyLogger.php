<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\Facades\File;

class AdminMonthlyLogger
{
    // mỗi ngày sẽ tự động tạo ra file log cho ngày đó
    public function __invoke(array $config)
    {
        $date = now()->format('Ymd');
        // đường dẫn chứa file log thư mục logs/central
        $logDir = storage_path("logs/central");

        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }
        // Tạo file log theo tháng: logs/central/central20240315.log
        $logPath = "{$logDir}/central{$date}.log";

        // Xử lý xoá nếu cần
        // $keepMonths = 6;
        // $oldDate = now()->subMonths($keepMonths)->format('Ymd');
        // $oldPath = "{$logDir}/central{$oldDate}.log";
        // if (File::exists($oldPath)) {
        //     File::delete($oldPath);
        // }

        $logger = new Logger('system_user_login');
        // Format log: [timestamp] [LEVEL] [IP] [route] : [message] {json_data}
        $output = "[%datetime%] %level_name% [%context.ip%] : %context.route% : %message% %context.data%\n";
        $formatter = new LineFormatter($output, 'Y-m-d H:i:s', true, true);
        $handler = new StreamHandler($logPath, Logger::INFO);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        return $logger;
    }
}
