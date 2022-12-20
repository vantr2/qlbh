@extends('layouts.app')
@php
    use App\Models\Order;
@endphp
@section('content')
    <div>
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ __('Create Order') }}</h4>
            </div>
            <div class="card-body">
                @include('orders._form')
            </div>
        </div>
    </div>
@endsection
