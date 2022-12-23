@extends('layouts.app')

@section('content')
    <div>
        <input type="hidden" id="require-list" value="name,address">
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ __('Update Company') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.store') }}" method="post">
                    <input type="hidden" name="_id" value="{{ $companyInfo->id }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Company Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $companyInfo->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('Address') }}</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="2" name="address">{{ old('address', $companyInfo->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="established_year" class="form-label">{{ __('Established Year') }}</label>
                                <input type="number" class="form-control @error('established_year') is-invalid @enderror"
                                    id="established_year" name="established_year"
                                    value="{{ old('established_year', $companyInfo->established_year ?? '') }}">
                                @error('established_year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mt-4">
                        <a class="btn btn-secondary px-3" href="{{ url()->previous() }}">{{ __('Back') }}</a>
                        <button type="submit" class="btn btn-primary ms-2">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
