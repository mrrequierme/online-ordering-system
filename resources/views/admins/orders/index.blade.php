@extends('layouts.authenticated')
@section('title', 'Pending Orders ' . config('app.name'))
@section('content')
    <div class="container">
        <div class="container text-center text-lg-start">
            <a href="{{ route('admin.orders.show') }}" class="btn btn-primary">View Approved Orders <i
                    class="fa-solid fa-hand-point-left"></i></a>
        </div>
        <h5 class="text-center py-4"><span class="bg-secondary text-white py-1 px-4">Pending Orders</span></h5>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders->groupBy(fn($order) => $order->claim_date->format('Y-m-d')) as $date => $dayOrders)
                        <tr class="text-center">
                            <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse"
                                    data-bs-target="#orders-{{ $loop->index }}">
                                    View Orders
                                </button>
                            </td>
                        </tr>

                        {{-- Collapsible row for orders under this date --}}
                        <tr class="collapse" id="orders-{{ $loop->index }}">
                            <td colspan="2">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dayOrders as $order)
                                            <tr>
                                                <td class="text-capitalize">{{ $order->user->name }}</td>
                                                <td>{{ $order->user->email }}</td>
                                                <td>{{ $order->user->contact }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-success text-capitalize">{{ $order->status }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="collapse"
                                                        data-bs-target="#products-{{ $order->id }}">
                                                        View Products
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- Collapsible row for products of this order --}}
                                            <tr class="collapse" id="products-{{ $order->id }}">
                                                <td colspan="5">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Price</th>
                                                                <th>Quantity</th>
                                                                <th>Line Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($order->products as $product)
                                                                <tr>
                                                                    <td>{{ $product->name }}</td>
                                                                    <td>₱{{ number_format($product->pivot->price, 2) }}
                                                                    </td>
                                                                    <td>{{ $product->pivot->qty }}</td>
                                                                    <td>₱{{ number_format($product->pivot->price * $product->pivot->qty, 2) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="3" class="text-end"><strong>Total</strong>
                                                                </td>
                                                                <td><strong>₱{{ number_format($order->total, 2) }}</strong>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success ms-2"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#approveModal-{{ $order->id }}">
                                                                        Approve
                                                                    </button>

                                                                    <!-- Bootstrap Modal -->
                                                                    <div class="modal fade"
                                                                        id="approveModal-{{ $order->id }}"
                                                                        tabindex="-1"
                                                                        aria-labelledby="approveModalLabel-{{ $order->id }}"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="approveModalLabel-{{ $order->id }}">
                                                                                        Confirm Approval</h5>
                                                                                    <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Are you sure you want to
                                                                                    <strong>approve</strong> this order?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-bs-dismiss="modal">Cancel</button>

                                                                                    <form
                                                                                        action="{{ route('admin.orders.approve', $order->id) }}"
                                                                                        method="POST" class="d-inline">
                                                                                        @csrf
                                                                                        @method('PATCH')
                                                                                        <button type="submit"
                                                                                            class="btn btn-success">Yes,
                                                                                            Approve</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
@endsection
