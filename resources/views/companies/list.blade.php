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

                <p class="float-end">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#company-filter-box" aria-expanded="false" aria-controls="company-filter-box">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </p>
                <div class="collapse mb-2" id="company-filter-box">
                    <div class="card card-body justify-content-center flex-row align-items-center">
                        {{-- Search company name --}}
                        <label for="search_name" class="fw-bold">{{ __('Company Name') }}</label>
                        <input type="text" id="search_name" value="" class="form-control w-auto ms-2">

                        {{-- Search company address --}}
                        <label for="search_address" class="ms-4 fw-bold">{{ __('Company Address') }}</label>
                        <input type="text" id="search_address" value="" class="form-control w-auto ms-2">

                        <button type="button" class="btn btn-primary ms-4" onclick="applySearch()">
                            <i class="fa-solid fa-circle-right"></i>
                        </button>
                    </div>
                </div>

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
        var companyTable;

        function applySearch() {
            companyTable.draw();
        }

        function confirmDeleteCompany(element) {
            if (confirm("{{ __('Do you want delete this company ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            companyTable = $('#tbl_companies').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: {
                    'type': 'get',
                    'url': '{!! route('companies.render_data') !!}',
                    'data': function(data) {
                        data.search_name = $('#search_name').val();
                        data.search_address = $('#search_address').val();
                    }
                },
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
