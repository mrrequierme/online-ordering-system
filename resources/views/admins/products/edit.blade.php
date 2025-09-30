@extends('layouts.authenticated')
@section('title','Edit Product ' . config('app.name'))
@section('content')
    <div class="container">
        <div class="row">


            <form action="{{ route('admin.products.update', ['product' => $product]) }}" method="post"
                enctype="multipart/form-data" class="product_form p-3 mt-3">
                @csrf
                @method('put')
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back</a>
                <h5 class="text-center text-primary">Update Product</h5>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        id="product_nameEdit" value="{{ $product->name }}" placeholder="Enter name here">
                    <label for="product_nameEdit">Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class=" form-floating mb-3">
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="priceEdit"
                        name="price" step="any" value="{{ $product->price }}" placeholder="Enter price">
                    <label for="priceEdit" class="form-label">Price</label>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                        placeholder="Products description." id="floatingTextareaEdit">{{ $product->description }}</textarea>
                    <label for="floatingTextareaEdit">Description</label>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>

                    @if (\Illuminate\Support\Str::endsWith(strtolower($product->image), ['.jpg', '.jpeg', '.png']))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="80"
                            class="img-thumbnail">
                    @else
                        <a href="{{ asset('storage/' . $product->image) }}" target="_blank">
                            View File
                        </a>
                    @endif
                </div>


                <div class="input-group mb-3">
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="inputGroupFile02"
                        name="image">
                    <label class="input-group-text" for="inputGroupFile02">Upload New</label>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <button type="submit" class="btn btn-primary btn-sm w-100">Submit</button>
            </form>

        </div>
    </div>
    
@endsection

