@if ($transactions->hasPages())
    <div class="d-flex justify-content-end mt-3">
        {{ $transactions->links() }}
    </div>
@endif
