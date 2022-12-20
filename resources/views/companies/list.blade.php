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
    <div>
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ __('Company Management') }}</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>

                <table id="tbl_companies" class="table table-hover table-bordered w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Company Name') }}</th>
                            <th>{{ __('Company Address') }}</th>
                            <th>{{ __('Established Year') }}</th>
                            <th>{{ __('Created By') }}</th>
                            <th>{{ __('Updated By') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
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
