<!-- Modal -->
<div class="modal fade" id="change_role_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('Change role setting') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_id" value="">
                    <div class="row">
                        <div class="col-12">
                            <strong class="me-4">{{ __('User Name') }}:</strong>
                            <span class="user-name"></span>
                        </div>
                        <div class="col-12 mt-3">
                            <strong class="me-4">{{ __('User Email') }}:</strong>
                            <span class="user-email"></span>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="role" class="form-label fw-bold">{{ __('User Role') }}</label>
                            @php
                                use App\Models\User;
                            @endphp
                            <select name="role" class="form-control form-select selectpicker-no-search">
                                <option value="{{ User::ADMIN }}">{{ __('Admin') }}</option>
                                <option value="{{ User::NORMAL_USER }}">{{ __('Normal User') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {

        })
    </script>
@endpush
