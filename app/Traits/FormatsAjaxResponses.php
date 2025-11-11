<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait FormatsAjaxResponses
{
    protected function calculatePerPage(Request $request, int $totalCountForAuth = 1000): int
    {
        $perPageInput = $request->input('per_page', 10);

        if ($perPageInput === 'all') {
            return max($totalCountForAuth, 1);
        }

        return (int) $perPageInput;
    }

    protected function ajaxResponse(string $viewPath, array $data, LengthAwarePaginator $paginator): JsonResponse
    {
        $table = view($viewPath, $data)->render();

        $pagination = $paginator
            ->appends(request()->query())
            ->links('vendor.pagination.custom')
            ->toHtml();

        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination
        ], 200);
    }

    protected function handleError(Request $request, string $message, int $statusCode = 500): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => $message
            ], $statusCode);
        }
        return back()->withErrors([
            'status' => false,
            'message' => $message
        ]);
    }
}
