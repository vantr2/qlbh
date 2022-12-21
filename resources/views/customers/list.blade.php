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
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ __('Customer Management') }}</h4>
            </div>
            <div class="card-body">
                @admin
                    <div class="d-flex">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>
                        <a href="{{ route('customers.export') }}" class="btn btn-primary mb-3 ms-2">{{ __('Export') }}</a>
                        <button type="button" class="btn btn-primary mb-3 ms-2" data-bs-toggle="modal"
                            data-bs-target="#import_customer_modal">
                            {{ __('Import') }}
                        </button>
                    </div>
                @endadmin

                <table id="tbl_customers" class="table table-hover table-bordered w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Age') }}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Birthday') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Workplace') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('customers.import_customer_modal')
@endsection

@push('scripts')
    <script>
        function confirmDeleteCustomer(element) {
            if (confirm("{{ __('Do you want delete this customer ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            $('#tbl_customers').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: '{!! route('customers.render_data') !!}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'birthday',
                        name: 'birthday'
                    },
                    {
                        data: 'type',
                        name: 'type'
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
