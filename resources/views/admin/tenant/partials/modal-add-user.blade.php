<div class="modal fade" id="chooseAdminModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header text-dark">
                <h5 class="modal-title text-dark" id="chooseAdminModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignAdminForm" method="POST">
                @csrf
                <div class="modal-body" style="height: 200px;">
                    <input type="hidden" id="selectedTenantId" name="tenant_id">
                    <div class="form-group mb-3">
                        <select id="adminAccountSelect" name="admin_id" class="form-control choices-select">
                            @foreach ($adminTenants as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->username }} ({{ $acc->display_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="saveAdminTenantBtn">
                        {{ __('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
