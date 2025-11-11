<?php

namespace App\Exceptions;

class FetchException extends BaseOperationException
{
    protected string $operation = 'fetch';
    protected string $messageKey = 'fetchErrorMessage';

    protected function getLogPrefix(): string
    {
        return 'Lấy dữ liệu thất bại';
    }
}
