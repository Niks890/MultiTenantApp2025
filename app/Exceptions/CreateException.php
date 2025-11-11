<?php

namespace App\Exceptions;

class CreateException extends BaseOperationException
{
    protected string $operation = 'create';
    protected string $messageKey = 'successErrorMessage';

    protected function getLogPrefix(): string
    {
        return 'Thêm mới thất bại';
    }
}
