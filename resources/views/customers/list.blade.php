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
                <div class="d-flex justify-content-between">
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

                    <p>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#customer-filter-box" aria-expanded="false" aria-controls="customer-filter-box">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </p>
                </div>
                <div class="collapse mb-2" id="customer-filter-box">
                    <div class="card card-body justify-content-center flex-row align-items-center">
                        {{-- Search customer name --}}
                        <label for="search_name" class="fw-bold">{{ __('Customer Name') }}</label>
                        <input type="text" id="search_name" value="" class="form-control w-auto ms-2">

                        {{-- Search customer age --}}
                        <label for="search_age_from" class="ms-4 fw-bold">{{ __('Customer Age') }}</label>
                        <input type="number" min="0" id="search_age_from" value=""
                            class="form-control search-box-number ms-2">
                        <span class="ms-2">~</span>
                        <input type="number" min="0" id="search_age_to" value=""
                            class="form-control search-box-number ms-2">

                        {{-- Search workplace --}}
                        <label for="search_workplace" class="fw-bold ms-4 me-2">{{ __('Workplace') }}</label>
                        <select id="search_workplace" class="form-control form-select selectpicker ms-2 w-auto">
                            <option value="">{{ __('Please choose ...') }}</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>

                        <button type="button" class="btn btn-primary ms-4" onclick="applySearch()">
                            <i class="fa-solid fa-circle-right"></i>
                        </button>
                    </div>
                </div>

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
        var customerTable;

        function applySearch() {
            customerTable.draw();
        }

        function confirmDeleteCustomer(element) {
            if (confirm("{{ __('Do you want delete this customer ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            customerTable = $('#tbl_customers').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: {
                    type: 'get',
                    url: '{!! route('customers.render_data') !!}',
                    data: function(data) {
                        data.search_name = $('#search_name').val();
                        data.search_age_from = $('#search_age_from').val();
                        data.search_age_to = $('#search_age_to').val();
                        data.search_workplace = $('#search_workplace').val();
                    }
                },
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
