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
                    <div class="d-flex">
                        <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>
                        <a href="{{ route('orders.export') }}" class="btn btn-primary mb-3 ms-2">{{ __('Export') }}</a>
                        <button type="button" class="btn btn-primary mb-3 ms-2" data-bs-toggle="modal"
                            data-bs-target="#import_order_modal">
                            {{ __('Import') }}
                        </button>
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
            function confirmDeleteOrder(element) {
                if (confirm("{{ __('Do you want delete this order ?') }}")) {
                    var url = $(element).data('href');
                    window.location.href = url;
                }
            }

            $(function() {
                $('#tbl_orders').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    ajax: '{!! route('orders.render_data') !!}',
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
                            data: 'workplace',
                            name: 'workplace'
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
