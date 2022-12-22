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
    @if (Session::has('import_error'))
        <div class="alert alert-danger">
            {!! Session::get('import_error') !!}
        </div>
    @endif
    <div>
        <div>
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">{{ __('Order Management') }}</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>
                            <a href="{{ route('orders.export') }}" class="btn btn-primary mb-3 ms-2">{{ __('Export') }}</a>
                            <button type="button" class="btn btn-primary mb-3 ms-2" data-bs-toggle="modal"
                                data-bs-target="#import_order_modal">
                                {{ __('Import') }}
                            </button>
                        </div>

                        <p>
                            <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#customer-filter-box" aria-expanded="false"
                                aria-controls="customer-filter-box">
                                <i class="fa-solid fa-filter"></i>
                            </button>
                        </p>
                    </div>

                    <div class="collapse mb-2" id="customer-filter-box">
                        <div class="card card-body justify-content-center flex-row align-items-center">
                            {{-- Search customer --}}
                            <label for="search_customer" class="fw-bold me-2">{{ __('Customer') }}</label>
                            <select id="search_customer" class="form-control form-select selectpicker ms-2 w-auto">
                                <option value="">{{ __('Please choose ...') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->first_name . ' ' . $customer->last_name }}</option>
                                @endforeach
                            </select>

                            {{-- Search order total --}}
                            <label for="search_total_from" class="ms-4 fw-bold">{{ __('Total range') }}</label>
                            <input type="number" min="0" id="search_total_from" value=""
                                class="form-control search-box-number ms-2">
                            <span class="ms-2">~</span>
                            <input type="number" min="0" id="search_total_to" value=""
                                class="form-control search-box-number ms-2">

                            {{-- Search order date --}}
                            <label for="search_date_from" class="ms-4 fw-bold">{{ __('Date') }}</label>
                            <input type="text" id="search_date_from" value="" autocomplete="off" readonly
                                class="form-control search-box-date ms-2 datepicker">
                            <span class="ms-2">~</span>
                            <input type="text" id="search_date_to" value="" autocomplete="off" readonly
                                class="form-control search-box-date ms-2 datepicker">

                            <button type="button" class="btn btn-primary ms-4" onclick="applySearch()">
                                <i class="fa-solid fa-circle-right"></i>
                            </button>
                        </div>
                    </div>

                    <table id="tbl_orders" class="table table-hover table-bordered w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Customer Name') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Order Date') }}</th>
                                <th>{{ __('Created By') }}</th>
                                <th>{{ __('Updated By') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @include('orders.import_order_modal')
    @endsection

    @push('scripts')
        <script>
            var orderTable;

            function applySearch() {
                orderTable.draw();
            }

            function confirmDeleteOrder(element) {
                if (confirm("{{ __('Do you want delete this order ?') }}")) {
                    var url = $(element).data('href');
                    window.location.href = url;
                }
            }

            $(function() {
                orderTable = $('#tbl_orders').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    ajax: {
                        type: 'get',
                        url: '{!! route('orders.render_data') !!}',
                        data: function(data) {
                            data.search_customer = $('#search_customer').val();
                            data.search_total_from = $('#search_total_from').val();
                            data.search_total_to = $('#search_total_to').val();
                            data.search_date_from = $('#search_date_from').val();
                            data.search_date_to = $('#search_date_to').val();
                        }
                    },
                    columns: [{
                            data: 'customer_name',
                            name: 'customer_name'
                        },
                        {
                            data: 'total',
                            name: 'total'
                        },
                        {
                            data: 'order_date',
                            name: 'order_date'
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
                        },
                        {
                            data: 'updated_by',
                            name: 'updated_by'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ]
                });
            });
        </script>
    @endpush
