@extends('layouts.authenticated')
@section('title','Products ' . config('app.name'))
@section('content')
    <div class="container">

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Add Product</a>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-responsive-sm">
        <table class="table table-striped border">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Added By</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="text-capitalize">{{ $product->name }}</td>
                        <td>₱{{ $product->price }}</td>
                        <td>{{ $product->description }}</td>
                        <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="card-img-top product_imgAdmin mx-auto d-block"></td>
                        <td>{{ $product->user->email }}</td>
                        <td class="d-flex gap-1 justify-content-center align-items-center"><a
                                href="{{ route('admin.products.edit', ['product' => $product->id]) }}"
                                class="btn btn-primary btn-sm">Edit</a>

                            <button type="button" class="btn btn-sm btn-danger ms-2" data-bs-toggle="modal"
                                data-bs-target="#deleteModal-{{ $product->id }}">
                                Delete
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm unclaimed</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to <strong>Delete</strong> this product, <strong class="text-capitalize">{{$product->name}}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <form
                                                action="{{ route('admin.products.destroy', ['product' => $product->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')

                                                <button class="btn btn-danger" type="submit">Yes, delete this product</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection
