<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Illuminate\Http\Request;

class CentralLogChannel
{
    protected $request;

    // 💡 Thêm hàm khởi tạo để inject Request
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Tạo một Monolog instance đã được tùy chỉnh.
     */
    public function __invoke(array $config): Logger
    {
        // 1. Logic tạo tên file (giữ nguyên)
        $startOfMonth = now()->startOfMonth();
        $dateForFilename = $startOfMonth->format('ymd');
        $filename = "central{$dateForFilename}.log";
        $path = storage_path('logs/central/' . $filename);
        $dateFormat = "Y-m-d H:i:s";

        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // 2. Định nghĩa Format cho dòng log
        // [%datetime%] %channel%.%level_name%: [%extra.ip%] %extra.url% : %message% %context% %extra%\n
        $output = "[%datetime%] %channel%.%level_name% [%context.request_ip%] /%context.request_uri% : %message% %context% %extra%\n";


        $formatter = new LineFormatter($output, $dateFormat, true, true);


        // 3. Tạo Handler và gán Formatter
        $handler = new StreamHandler(
            $path,
            $config['level'] ?? Level::Info
        );
        $handler->setFormatter($formatter);

        // 4. Tạo Logger
        $logger = new Logger('auth', [$handler]);

        return $logger;
    }
}
