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
    <div class="container">
        <h1 class="mb-3">{{ __('Customer Management') }}</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>

        <table id="tbl_customers" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Age') }}</th>
                    <th>{{ __('Gender') }}</th>
                    <th>{{ __('Birthday') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Workplace') }}</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
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
