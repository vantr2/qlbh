@extends('layouts.app')
@php
    use App\Models\Order;
@endphp
@section('content')
    <div>
        <div>
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">{{ __('Update Order') }}</h4>
                </div>
                <div class="card-body">
                    <a class="btn btn-secondary mb-3" href="{{ route('orders.list') }}">{{ __('Back') }}</a>
                    <form action="{{ route('orders.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="_id" value="{{ $orderInfo->id }}">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name"
                                        value="{{ old('first_name', $orderInfo->first_name) }}">
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
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name"
                                        value="{{ old('last_name', $orderInfo->last_name) }}">
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
                                    <input type="number" class="form-control @error('age') is-invalid @enderror"
                                        id="age" name="age" value="{{ old('age', $orderInfo->age) }}">
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
                                    <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                        id="birthday" name="birthday" value="{{ old('birthday', $orderInfo->birthday) }}">

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
                                            value="{{ Order::MALE }}"
                                            {{ $orderInfo->gender == Order::MALE ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">{{ __('Male') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                            value="{{ Order::FEMALE }}"
                                            {{ $orderInfo->gender == Order::FEMALE ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">{{ __('Female') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other"
                                            value="{{ Order::OTHER }}"
                                            {{ $orderInfo->gender == Order::OTHER ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">{{ __('Other') }}</label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="2" name="address">{{ old('address', $orderInfo->address) }}</textarea>
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
                                        <option value="{{ Order::VIP }}"
                                            {{ $orderInfo->type == Order::VIP ? 'selected' : '' }}>{{ __('VIP') }}
                                        </option>
                                        <option value="{{ Order::NORMAL }}"
                                            {{ $orderInfo->type == Order::NORMAL ? 'selected' : '' }}>{{ __('Normal') }}
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
                                                {{ $orderInfo->company_id == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
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
            </div>
        </div>
    @endsection
