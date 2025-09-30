@extends('layouts.authenticated')

@section('content')
    <div class="container">
        <h5 class="my-4 "><i class="fa-solid fa-cart-shopping "><span class="fs-6">Cart</span></i> </h5>

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }} <a href="{{ route('user.products.index') }}" class="btn btn-primary btn-sm">Open
                    cart</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Status label (shows DB pending status or "none") --}}
        <p>Status:
            <span id="orderStatus" class="bg-primary-subtle py-1 px-2 text-capitalize rounded">
                {{ $pendingOrder ? $pendingOrder->status : 'none' }}
            </span>
        </p>

        {{-- Order table (always present so JS can work) --}}
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
                {{-- If a DB pending order exists, Blade renders those rows so JS can read them --}}
                @if ($pendingOrder)
                    @foreach ($pendingOrder->products as $product)
                        <tr data-id="{{ $product->id }}">
                            <td>{{ $product->name }}</td>
                            <td class="qty">{{ $product->pivot->qty }}</td>
                            <td class="price">₱{{ number_format($product->pivot->price, 2) }}</td>
                            <td class="subtotal">₱{{ number_format($product->pivot->qty * $product->pivot->price, 2) }}</td>
                            <td><button type="button" class="btn btn-warning btn-sm editQty"
                                    data-id="{{ $product->id }}">Edit</button></td>
                            <td><button type="button" class="btn btn-danger btn-sm deleteItem"
                                    data-id="{{ $product->id }}">Delete</button></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <h4>Total: ₱<span id="totalPrice">{{ $pendingOrder ? number_format($pendingOrder->total, 2) : '0.00' }}</span></h4>
  
        <form id="orderForm" action="{{ route('user.orders.store') }}" method="POST">
    @csrf

    <div class="mb-3 row">
        <div class="col-sm-12 col-md-5 col-lg-2">
        <label for="claim_date" class="form-label">
            Schedule Date:
            <i class="bi bi-info-circle text-primary ms-1 fs-5"
               style="cursor: pointer;"
               data-bs-toggle="popover"
               data-bs-trigger="hover focus click"
               data-bs-placement="right"
               data-bs-content="You will be contacted by our staff to confirm the claim or pickup arrangements, including the address and any additional instructions">
            </i>
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

    <button type="submit" id="orderNowBtn" class="btn btn-success">
        Order Now
    </button>
</form>


        {{-- Approved Orders (always visible below) --}}
        <div class="row">
            <div class="col-md-12 col-lg-6 mx-auto">
                <h5 class="mt-5 text-center"><i class="fa-solid fa-file-lines"></i>Records</h5>
                @forelse($approvedOrders as $order)
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="card-title">
                                <span
                                    class="text-uppercase py-1 px-2 rounded 
        {{ in_array($order->status, ['approved', 'done']) ? 'bg-info-subtle' : 'bg-warning-subtle' }}">
                                    {{ $order->status }}
                                </span>
                            </h6>

                            <ul>
                                @foreach ($order->products as $product)
                                    <li>{{ $product->name }} - {{ $product->pivot->qty }} pcs @
                                        ₱{{ number_format($product->pivot->price, 2) }}</li>
                                @endforeach
                            </ul>
                            <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
                            <p class="mb-0"><strong>Date Approved:</strong> {{ $order->claim_date->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No orders recorded yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tbody = document.querySelector("#orderTable tbody");
            const totalPriceEl = document.getElementById("totalPrice");
            const cartInput = document.getElementById("cartInput");
            const orderDateEl = document.getElementById("orderDate");
            const orderForm = document.getElementById("orderForm");

            // Helpers
            function parsePrice(raw) {
                if (!raw) return 0;
                if (typeof raw === "number") return raw;
                return parseFloat(String(raw).replace(/[^\d.\-]/g, "")) || 0;
            }

            function formatPrice(n) {
                return Number(n).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function escapeHtml(s) {
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                    '&quot;').replace(/'/g, '&#039;');
            }

            // Pending order id from backend
            const pendingOrderId = @json($pendingOrder ? $pendingOrder->id : null);

            let cart = [];

            if (pendingOrderId) {
                // Load DB items (from blade)
                tbody.querySelectorAll("tr").forEach(row => {
                    cart.push({
                        id: String(row.dataset.id),
                        name: row.querySelector("td:first-child").innerText.trim(),
                        qty: parseInt(row.querySelector(".qty").innerText) || 0,
                        price: parsePrice(row.querySelector(".price").innerText)
                    });
                });

                // Merge session cart ONCE
                let stored = [];
                try {
                    stored = JSON.parse(sessionStorage.getItem("cart") || "[]");
                } catch (e) {}
                const alreadyMerged = sessionStorage.getItem("mergedFor_" + pendingOrderId) === "1";

                if (stored.length > 0 && !alreadyMerged) {
                    stored.forEach(s => {
                        const existing = cart.find(c => c.id === String(s.id));
                        if (existing) {
                            existing.qty = Number(s.qty); // ✅ replace qty, don’t add
                        } else {
                            cart.push({
                                id: String(s.id),
                                name: s.name,
                                qty: Number(s.qty),
                                price: parsePrice(s.price)
                            });
                        }
                    });
                    sessionStorage.setItem("mergedFor_" + pendingOrderId, "1"); // ✅ mark merged once
                }

            } else {
                // No DB order → only use sessionStorage
                try {
                    cart = JSON.parse(sessionStorage.getItem("cart") || "[]");
                } catch (e) {}
            }

            // Render function
            function renderTable() {
                tbody.innerHTML = "";
                let total = 0;

                cart.forEach(item => {
                    const subtotal = item.qty * item.price;
                    total += subtotal;
                    const tr = document.createElement("tr");
                    tr.dataset.id = item.id;
                    tr.innerHTML = `
                <td>${escapeHtml(item.name)}</td>
                <td class="qty">${item.qty}</td>
                <td class="price">₱${formatPrice(item.price)}</td>
                <td class="subtotal">₱${formatPrice(subtotal)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-warning btn-sm editQty" data-id="${item.id}">Edit</button>
                <button type="button" class="btn btn-danger btn-sm deleteItem" data-id="${item.id}">Delete</button>
                </td>
            `;
                    tbody.appendChild(tr);
                });

                totalPriceEl.innerText = formatPrice(total);
                cartInput.value = JSON.stringify(cart);
                sessionStorage.setItem("cart", JSON.stringify(cart));
            }

            renderTable();

            // Edit/Delete with delegation
            tbody.addEventListener("click", e => {
                const id = e.target.dataset.id;
                if (!id) return;

                if (e.target.classList.contains("editQty")) {
                    const item = cart.find(c => c.id === id);
                    const newQty = prompt("Enter new quantity", item.qty);
                    if (newQty && parseInt(newQty) > 0) {
                        item.qty = parseInt(newQty);
                        renderTable();
                    }
                }

                if (e.target.classList.contains("deleteItem")) {
                    cart = cart.filter(c => c.id !== id);
                    renderTable();
                }
            });

            // On form submit → latest cart
            orderForm.addEventListener("submit", function() {
                cartInput.value = JSON.stringify(cart);
            });

            // On successful save → clear cart
            @if (session('success'))
                sessionStorage.removeItem("cart");
                sessionStorage.removeItem("cartNeedsMerge");
                Object.keys(sessionStorage).forEach(k => {
                    if (k.startsWith("mergedFor_")) sessionStorage.removeItem(k);
                });
            @endif
        });


        document.addEventListener("DOMContentLoaded", function() {
            let currentUserId = "{{ auth()->id() }}";
            let storedUserId = sessionStorage.getItem("cart_user");

            if (storedUserId !== currentUserId) {
                // New user → reset cart BEFORE rendering
                sessionStorage.clear();
                sessionStorage.setItem("cart_user", currentUserId);
            }

            // Now continue with your renderCart or merge logic
            renderCart();
        });


        document.addEventListener("DOMContentLoaded", function () {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].forEach(el => new bootstrap.Popover(el));
});
    </script>


@endsection
