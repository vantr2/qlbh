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
            <tbody>
                @foreach ($companies as $company)
                    <tr>
                        <td class="align-middle">{{ $company->name }}</td>
                        <td class="align-middle">{{ $company->address }}</td>
                        <td class="align-middle">{{ $company->established_year }}</td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('companies.detail', ['id' => $company->id]) }}"
                                    class="btn btn-warning me-2">{{ __('Update') }}</a>
                                <button data-href="{{ route('companies.delete', ['id' => $company->id]) }}"
                                    onclick="confirmDeleteCompany(this)"
                                    class="btn btn-danger">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeleteCompany(element) {
            if (confirm('{{ __('Do you want delete this company ?') }}')) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            $('#tbl_companies').DataTable();
        });
    </script>
@endpush
