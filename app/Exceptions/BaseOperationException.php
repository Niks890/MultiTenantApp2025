<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseOperationException extends Exception
{
    protected string $operation;
    protected string $resource;
    protected string $messageKey;

    public function __construct(
        string $resource = '',
        string $detail = '',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null
    ) {
        $this->resource = $resource;
        $message = $detail ?: __($this->messageKey);
        $finalCode = $code ?: Response::HTTP_INTERNAL_SERVER_ERROR;
        parent::__construct($message, $finalCode, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        $logMessage = $this->getLogPrefix() .
            ($this->resource ? " [{$this->resource}]" : '') .
            ': ' . $this->getMessage();

        $context = [
            'operation' => $this->operation,
            'resource' => $this->resource,
            'exception' => get_class($this),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ];

        if ($previous = $this->getPrevious()) {
            $context['previous_exception'] = get_class($previous);
            $context['previous_message'] = $previous->getMessage();
            $context['original_file'] = $previous->getFile();
            $context['original_line'] = $previous->getLine();
        }

        Log::error($logMessage, $context);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => $this->getMessage()
            ], $this->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return back()->withErrors([
            'status' => false,
            'message' => $this->getMessage()
        ])->withInput();
    }

    abstract protected function getLogPrefix(): string;
}
