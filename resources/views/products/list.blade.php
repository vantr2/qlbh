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
        <h1 class="mb-3">{{ __('Product Management') }}</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">{{ __('Add') }}</a>

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
@endsection

@push('scripts')
    <script>
        function confirmDeleteProduct(element) {
            if (confirm("{{ __('Do you want delete this product ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            $('#tbl_products').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: '{!! route('products.render_data') !!}',
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
