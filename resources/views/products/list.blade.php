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
        <div>
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">{{ __('Product Management') }}</h4>
                </div>
                <div class="card-body">
                    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>

                    <p class="float-end">
                        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#product-filter-box" aria-expanded="false" aria-controls="product-filter-box">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </p>
                    <div class="collapse mb-2" id="product-filter-box">
                        <div class="card card-body justify-content-center flex-row align-items-center">
                            {{-- Search product name --}}
                            <label for="search_name" class="fw-bold">{{ __('Product Name') }}</label>
                            <input type="text" id="search_name" value="" class="form-control w-auto ms-2">

                            {{-- Search product price --}}
                            <label for="search_price_from" class="ms-4 fw-bold">{{ __('Product Price') }}</label>
                            <input type="number" min="0" id="search_price_from" value=""
                                class="form-control search-box-number ms-2">
                            <span class="ms-2">~</span>
                            <input type="number" min="0" id="search_price_to" value=""
                                class="form-control search-box-number ms-2">

                            <button type="button" class="btn btn-primary ms-4" onclick="applySearch()">
                                <i class="fa-solid fa-circle-right"></i>
                            </button>
                        </div>
                    </div>

                    <table id="tbl_products" class="table table-hover table-bordered w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Price') }}</th>
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
            var productTable;

            function applySearch() {
                productTable.draw();
            }

            function confirmDeleteProduct(element) {
                if (confirm("{{ __('Do you want delete this product ?') }}")) {
                    var url = $(element).data('href');
                    window.location.href = url;
                }
            }

            $(function() {
                productTable = $('#tbl_products').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    ajax: {
                        type: 'get',
                        url: '{!! route('products.render_data') !!}',
                        data: function(data) {
                            data.search_name = $('#search_name').val();
                            data.search_price_from = $('#search_price_from').val();
                            data.search_price_to = $('#search_price_to').val();
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'price',
                            name: 'price'
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
