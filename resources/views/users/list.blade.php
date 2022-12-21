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
        function confirmDeleteUser(element) {
            if (confirm("{{ __('Do you want delete this user ?') }}")) {
                var url = $(element).data('href');
                window.location.href = url;
            }
        }

        $(function() {
            $('#tbl_users').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                ajax: '{!! route('users.render_data') !!}',
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
