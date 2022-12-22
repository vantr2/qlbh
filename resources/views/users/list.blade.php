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
                <h4 class="mb-0">{{ __('User Management') }}</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <p class="float-end">
                        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#user-filter-box" aria-expanded="false" aria-controls="user-filter-box">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </p>
                </div>

                <div class="collapse mb-2" id="user-filter-box">
                    <div class="card card-body justify-content-center flex-row align-items-center">
                        {{-- Search user name --}}
                        <label for="search_name" class="fw-bold">{{ __('User Name') }}</label>
                        <input type="text" id="search_name" value="" class="form-control w-auto ms-2">

                        {{-- Search user email --}}
                        <label for="search_email" class="fw-bold ms-4">{{ __('User Email') }}</label>
                        <input type="text" id="search_email" value="" class="form-control w-auto ms-2">

                        {{-- Search user name --}}
                        <label for="search_role" class="fw-bold ms-4">{{ __('User Role') }}</label>
                        <select id="search_role" class="form-control form-select w-auto ms-2">
                            <option value="">{{ __('Please choose ...') }}</option>
                            <option value="{{ \App\Models\User::ADMIN }}">{{ __('Admin') }}</option>
                            <option value="{{ \App\Models\User::NORMAL_USER }}">{{ __('Normal User') }}</option>
                        </select>

                        <button type="button" class="btn btn-primary ms-4" onclick="applySearch()">
                            <i class="fa-solid fa-circle-right"></i>
                        </button>
                    </div>
                </div>

                <table id="tbl_users" class="table table-hover table-bordered w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Updated Date') }}</th>
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
        var userTable;

        function applySearch() {
            userTable.draw();
        }

        function confirmDeleteUser(element) {
            if (confirm("{{ __('Do you want delete this user ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            userTable = $('#tbl_users').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: {
                    type: 'get',
                    url: '{!! route('users.render_data') !!}',
                    data: function(data) {
                        data.search_name = $('#search_name').val();
                        data.search_email = $('#search_email').val();
                        data.search_role = $('#search_role').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
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
