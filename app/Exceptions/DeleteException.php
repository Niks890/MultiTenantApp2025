<?php

namespace App\Exceptions;

class DeleteException extends BaseOperationException
{
    protected string $operation = 'delete';
    protected string $messageKey = 'deleteErrorMessage';

    protected function getLogPrefix(): string
    {
        return 'Xóa thất bại';
    }
}
