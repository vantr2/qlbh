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
    <div class="card">
        <h4 class="card-header">{{ __('Profile') }}</h4>

        <div class="card-body">
            <form action="{{ route('profile.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name" class="form-label">{{ __('User Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $me->name) }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="text" readonly class="form-control @error('email') is-invalid @enderror"
                                id="email" value="{{ old('email', $me->email) }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 mt-3">
                        <input type="file" class="form-control form-control-user @error('file') is-invalid @enderror"
                            id="exampleFile" name="file" value="{{ old('file') }}" accept="image/*">
                        @error('file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-6 mt-3">
                        <img class="img-thumbnail" src="{{ '/images' . '/' . ($me->avatar ?? 'no-user.png') }}"
                            alt="{{ __('Avatar') }}" width="200">
                    </div>
                </div>

                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
