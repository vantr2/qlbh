@php
    use Illuminate\Support\Facades\Log;
    $assignee = [];
    if (isset($customerInfo)) {
        if ($customerInfo->user_ids) {
            $assignee = $customerInfo->user_ids;
        }
    }
    
    $oldValue = old('user_ids', $assignee);
@endphp

@admin
    <div class="col-12 mb-3">
        <label for="user_ids" class="form-label">{{ __('Assignee') }}</label>
        <select id="user_ids" name="user_ids[]"
            class='form-select form-control selectpicker @error('user_ids') is-invalid @enderror' multiple="multiple">
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ in_array($user->id, $oldValue) ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        <div class="d-flex">
            <button type="button" class="btn btn-link" onclick="selectAllUser()">{{ __('Select All') }}</button>
            <button type="button" class="btn btn-link" onclick="deSelectAllUser()">{{ __('Deselect All') }}</button>
        </div>
        @error('user_ids')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
@endadmin

@push('scripts')
    <script>
        function selectAllUser() {
            $("#user_ids > option").prop("selected", "selected").trigger("change");
        }

        function deSelectAllUser() {
            $('#user_ids').val('').trigger('change')
        }
    </script>
@endpush
