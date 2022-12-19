@extends('layouts.app')
@php
    use App\Models\Customer;
@endphp
@section('content')
    <div class="container">
        <h1 class="mb-3">{{ __('Update Customer') }}</h1>
        <a class="btn btn-secondary mb-3" href="{{ route('customers.list') }}">{{ __('Back') }}</a>
        <form action="{{ route('customers.store') }}" method="post">
            @csrf
            <input type="hidden" name="_id" value="{{ $customerInfo->id }}">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                            name="first_name" value="{{ old('first_name', $customerInfo->first_name) }}">
                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                            name="last_name" value="{{ old('last_name', $customerInfo->last_name) }}">
                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <label for="age" class="form-label">{{ __('Age') }}</label>
                        <input type="number" class="form-control @error('age') is-invalid @enderror" id="age"
                            name="age" value="{{ old('age', $customerInfo->age) }}">
                        @error('age')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <label for="birthday" class="form-label">{{ __('Birthday') }}</label>
                        <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday"
                            name="birthday" value="{{ old('birthday', $customerInfo->birthday) }}">

                        @error('birthday')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male"
                                value="{{ Customer::MALE }}"
                                {{ $customerInfo->gender == Customer::MALE ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">{{ __('Male') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female"
                                value="{{ Customer::FEMALE }}"
                                {{ $customerInfo->gender == Customer::FEMALE ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">{{ __('Female') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="other"
                                value="{{ Customer::OTHER }}"
                                {{ $customerInfo->gender == Customer::OTHER ? 'checked' : '' }}>
                            <label class="form-check-label" for="other">{{ __('Other') }}</label>
                        </div>

                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('Address') }}</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="2" name="address">{{ old('address', $customerInfo->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __('Type') }}</label>
                        <select name="type" class='form-select form-control'>
                            <option value="">{{ __('Please choose...') }}</option>
                            <option value="{{ Customer::VIP }}"
                                {{ $customerInfo->type == Customer::VIP ? 'selected' : '' }}>{{ __('VIP') }}
                            </option>
                            <option value="{{ Customer::NORMAL }}"
                                {{ $customerInfo->type == Customer::NORMAL ? 'selected' : '' }}>{{ __('Normal') }}
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <label for="company_id" class="form-label">{{ __('Workplace') }}</label>
                        <select name="company_id" class='form-select form-control'>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $customerInfo->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </form>
    </div>
@endsection
