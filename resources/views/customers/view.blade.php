@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header py-3">
            <h4 class="mb-0">{{ __('Customer Info') }}</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-10">
                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Name') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->first_name . ' ' . $customerInfo->last_name }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Age') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->age ?? '' }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Gender') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->genderToText() ?? '' }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Birthday') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->birthday ? $customerInfo->birthday->format('d/m/Y') : '' }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Address') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->address ?? '' }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Type') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->typeToText() }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <strong class="me-1">{{ __('Workplace') }}:</strong>
                        </div>
                        <div class="col-9">
                            <span>{{ $customerInfo->company ? $customerInfo->company->name : '' }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-3">

                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex">
                <a class="btn btn-secondary px-3 me-2" href="{{ url()->previous() }}">{{ __('Back') }}</a>
                <a class="btn btn-warning"
                    href="{{ route('customers.detail', ['id' => request('id')]) }}">{{ __('Update') }}</a>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header py-3">
            <h4 class="mb-0">{{ __('Order Info') }}</h4>
        </div>

        <div class="card-body">
            @if (!$customerInfo->orders->count())
                <p class="mt-3">{{ __('This customer currently has no orders.') }}</p>
            @else
                <table id="tbl_orders_customer" class="table table-hover table-bordered w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Created By') }}</th>
                            <th>{{ __('Updated By') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customerInfo->orders as $order)
                            <tr>
                                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total) }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</td>
                                <td>{{ \App\Helpers\Utils::actionUser($order->created_by) }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($order->updated_at)) }}</td>
                                <td>{{ \App\Helpers\Utils::actionUser($order->updated_by) }}</td>
                                <td>
                                    <a href="{{ route('orders.detail', ['id' => $order->id]) }}"
                                        class="btn btn-secondary">{{ __('Detail') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
@endsection
