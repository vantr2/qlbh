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
        <select name="user_ids[]" class='form-select form-control selectpicker @error('user_ids') is-invalid @enderror'
            multiple="multiple">
            <option value="" disabled>{{ __('Please choose...') }}</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ in_array($user->id, $oldValue) ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        @error('user_ids')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
@endadmin
