@extends('layouts.authenticated')
@section('title','Products ' . config('app.name'))
@section('content')
    <div class="container py-3">
        <div class="sticky-top p-2 text-end" style="top:80px;">
            <button id="addToCartBtn" class="btn btn-primary">Add to Cart</button>
        </div>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-sm-12 col-md-6 col-lg-3 mx-auto mb-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product_img"
                            alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title text-capitalize">{{ $product->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">₱{{ number_format($product->price, 2) }}</h6>
                            <p class="card-text text-start">{{ $product->description }}</p>

                            <!-- Quantity selector -->
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-sm btn-outline-secondary decreaseQty"
                                    data-id="{{ $product->id }}">-</button>
                                <input type="number" class="form-control mx-2 text-center productQty" style="width:70px;"
                                    value="0" min="0" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                <button class="btn btn-sm btn-outline-secondary increaseQty"
                                    data-id="{{ $product->id }}">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="selectProductModal" tabindex="-1" aria-labelledby="selectProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectProductLabel">Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please select at least one product.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.orderIndexUrl = "{{ route('user.orders.index') }}";
    </script>
    <script src="{{ asset('js/products.js') }}"></script>
@endpush
