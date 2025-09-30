@extends('layouts.authenticated')
@section('title','My Orders ' . config('app.name'))
@section('content')
<div class="container">
    <h5 class="my-4">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="fs-6">Cart</span>
    </h5>

    {{-- Flash Messages --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <a href="{{ route('user.products.index') }}" class="btn btn-primary btn-sm">Open cart</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
    <script>window.orderSubmitted = true;</script>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Status label --}}
    <p>Status:
        <span id="orderStatus" class="bg-primary-subtle py-1 px-2 text-capitalize rounded">
            {{ $pendingOrder ? $pendingOrder->status : 'none' }}
        </span>
    </p>

    {{-- Order Table --}}
    <div class="table-responsive-sm">
    <table class="table table-bordered" id="orderTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- CASE: pending order in DB --}}
            @if ($pendingOrder && $pendingOrder->status === 'pending')
                @foreach ($pendingOrder->products as $product)
                    <tr data-id="{{ $product->id }}">
                        <td>{{ $product->name }}</td>
                        <td class="qty">{{ $product->pivot->qty }}</td>
                        <td class="price">₱{{ number_format($product->pivot->price, 2) }}</td>
                        <td class="subtotal">₱{{ number_format($product->pivot->qty * $product->pivot->price, 2) }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm editQty" data-id="{{ $product->id }}">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm deleteItem" data-id="{{ $product->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            {{-- CASE: no pending order → tbody stays empty, JS populates from sessionStorage --}}
        </tbody>
    </table>
    </div>
    {{-- Include Modals --}}
    @include('users.orders.partials.modals')

    <h4>Total: ₱
        <span id="totalPrice">
            {{ ($pendingOrder && $pendingOrder->status === 'pending')
                ? number_format($pendingOrder->total, 2)
                : '0.00' }}
        </span>
    </h4>

    {{-- Order Form --}}
    <form id="orderForm" action="{{ route('user.orders.store') }}" method="POST">
        @csrf
        <div class="mb-3 row">
            <div class="col-sm-12 col-md-5 col-lg-2">
                <i class="bi bi-info-circle text-primary ms-1 fs-5"
                       data-bs-toggle="popover"
                       data-bs-trigger="hover focus click"
                       data-bs-placement="right"
                       data-bs-content="Once your order has been approved, our staff will contact you to confirm the claim or pickup arrangements, including the address and any additional instructions. Orders placed today will be processed within the same day.">
                    </i>
                <label for="claim_date" class="form-label">
                    Schedule Date:
                    
                </label>
                <input type="date"
                       id="claim_date"
                       name="claim_date"
                       class="form-control"
                       min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                       value="{{ old('claim_date', $pendingOrder ? $pendingOrder->claim_date?->format('Y-m-d') : '') }}"
                       required>
            </div>
        </div>

        <input type="hidden" name="cart" id="cartInput">
        <button type="submit" id="orderNowBtn" class="btn btn-success">Order Now</button>
    </form>

    {{-- Approved/Done Orders --}}
    <div class="row">
        <div class="col-md-12 col-lg-6 mx-auto">
            <h5 class="mt-5 text-center"><i class="fa-solid fa-file-lines"></i> Records</h5>
            @forelse($approvedOrders as $order)
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <span class="text-uppercase py-1 px-2 rounded 
                                {{ in_array($order->status, ['approved', 'done']) ? 'bg-primary-subtle' : 'bg-danger-subtle' }}">
                                {{ $order->status }}
                            </span>
                        </h6>
                        <ul>
                            @foreach ($order->products as $product)
                                <li>{{ $product->name }} - {{ $product->pivot->qty }} pcs @ ₱{{ number_format($product->pivot->price, 2) }}</li>
                            @endforeach
                        </ul>
                        <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
                        <div class="mb-0 d-block d-lg-flex justify-content-lg-between">
                            <p><strong>Date Ordered:</strong> {{ $order->created_at->format('d M Y') }}</p>
                            <p><strong>Claim Date:</strong> {{ $order->claim_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No orders recorded yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.pendingOrderId = @json($pendingOrder?->id);
    window.pendingOrderStatus = @json($pendingOrder?->status);
    window.currentUserId = "{{ auth()->id() }}";
</script>
<script src="{{ asset('js/cart.js') }}"></script>
@endpush
