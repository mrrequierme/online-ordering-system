@extends('layouts.authenticated')
@section('title','Create Product ' . config('app.name'))
@section('content')
    <div class="container">
        <div class="row">


            <form action="{{route('admin.products.store')}}" method="post" enctype="multipart/form-data" class="product_form p-3 mt-3 col-md-12 col-lg-7 mx-auto">
                @csrf

                <a href="{{route('admin.products.index')}}" class="btn btn-primary btn-sm">Back</a>
                <h5 class="text-center">Add Product</h5>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        id="product_name" placeholder="Enter name here">
                    <label for="product_name">Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" step="0.01" placeholder="Enter price">
                    <label for="price" class="form-label">Price</label>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                        placeholder="Products description." id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">Description</label>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="inputGroupFile02"
                        name="image">
                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-100">Submit</button>
            </form>
        </div>
    </div>
@endsection
