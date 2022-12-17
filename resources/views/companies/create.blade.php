@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-secondary mb-3" href="{{ route('companies.list') }}">{{ __('Back') }}</a>
        <form action="{{ route('companies.store') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Company Name') }}</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', '') }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">{{ __('Address') }}</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="2" name="address">{{ old('address', '') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="established_year" class="form-label">{{ __('Established Year') }}</label>
                <input type="number" class="form-control @error('established_year') is-invalid @enderror"
                    id="established_year" name="established_year" value="{{ old('established_year', '') }}">
                @error('established_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </form>
    </div>
@endsection
