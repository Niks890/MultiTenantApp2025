<?php

namespace App\Exceptions;

class UpdateException extends BaseOperationException
{
    protected string $operation = 'update';
    protected string $messageKey = 'successErrorMessage';

    protected function getLogPrefix(): string
    {
        return 'Cập nhật thất bại';
    }
}
