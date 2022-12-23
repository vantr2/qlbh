@extends('layouts.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('fail'))
        <div class="alert alert-danger">
            {{ Session::get('fail') }}
        </div>
    @endif
    <div>
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ __('User Setting Permission') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.apply_setting') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $userId }}">
                    <div class="row">
                        @foreach ($customers as $customer)
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $customer->id }}"
                                        id="{{ $customer->id }}" name="permissions[]"
                                        {{ in_array($userId, $customer->beApplied->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $customer->id }}">
                                        {{ $customer->first_name . '' . $customer->last_name }}
                                    </label>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex mt-4">
                        <a class="btn btn-secondary px-3" href="{{ url()->previous() }}">{{ __('Back') }}</a>
                        <button type="submit" class="btn btn-primary ms-2">{{ __('Apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
