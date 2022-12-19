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
        <h1 class="mb-3">{{ __('Company Management') }}</h1>
        <a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>

        <table id="tbl_companies" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Company Name') }}</th>
                    <th>{{ __('Company Address') }}</th>
                    <th>{{ __('Established Year') }}</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeleteCompany(element) {
            if (confirm("{{ __('Do you want delete this company ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            $('#tbl_companies').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: '{!! route('companies.render_data') !!}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'established_year',
                        name: 'established_year'
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
