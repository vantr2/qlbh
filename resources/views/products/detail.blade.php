@extends('layouts.app')

@section('content')
    <div>
        <h1 class="mb-3">{{ __('Update Product') }}</h1>
        <a class="btn btn-secondary mb-3" href="{{ route('products.list') }}">{{ __('Back') }}</a>
        <form action="{{ route('products.store') }}" method="post">
            <input type="hidden" name="_id" value="{{ $productInfo->id }}">
            @csrf

            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Product Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $productInfo->name) }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-3">
                        <label for="price" class="form-label">{{ __('Price') }}</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                            name="price" value="{{ old('price', $productInfo->price) }}">
                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3"
                            name="description">{{ old('description', $productInfo->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </form>
    </div>
@endsection
