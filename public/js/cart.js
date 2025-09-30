document.addEventListener("DOMContentLoaded", function () {
    // === 1. Reset sessionStorage if order was just submitted ===
    if (window.orderSubmitted || document.querySelector(".alert-success")) {
        sessionStorage.removeItem("cart"); // clear cart after successful order

        // optional: clear any "mergedFor_x" flags from previous pending orders
        Object.keys(sessionStorage).forEach((k) => {
            if (k.startsWith("mergedFor_")) sessionStorage.removeItem(k);
        });
    }

    // === 2. DOM references ===
    const tbody = document.querySelector("#orderTable tbody"); // cart table body
    const totalPriceEl = document.getElementById("totalPrice"); // total price display
    const cartInput = document.getElementById("cartInput"); // hidden input for form submission

    // Helper: parse string "₱1,000.00" → 1000
    function parsePrice(text) {
        return parseFloat((text || "").replace(/[^\d.-]/g, "")) || 0;
    }

    let cart = [];

    // === 3. Load cart data ===
    if (!window.pendingOrderId || window.pendingOrderStatus !== "pending") {
        // CASE A: No pending order → load directly from sessionStorage
        try {
            cart = JSON.parse(sessionStorage.getItem("cart") || "[]");
        } catch (e) {
            cart = [];
        }
    } else if (
        window.pendingOrderId &&
        window.pendingOrderStatus === "pending"
    ) {
        // CASE B: Order exists in DB with status "pending"

        // 1. Load existing cart rows from DB-rendered table
        tbody.querySelectorAll("tr").forEach((row) => {
            cart.push({
                id: String(row.dataset.id),
                name: row.querySelector("td:first-child").innerText.trim(),
                qty: parseInt(row.querySelector(".qty").innerText) || 0,
                price: parsePrice(row.querySelector(".price").innerText),
            });
        });

        // 2. Merge items from sessionStorage (newly added products)
        let stored = [];
        try {
            stored = JSON.parse(sessionStorage.getItem("cart") || "[]");
        } catch (e) {}

        stored.forEach((s) => {
            const existing = cart.find((c) => String(c.id) === String(s.id));
            if (existing) {
                existing.qty += Number(s.qty); // if product exists → add qty
            } else {
                // if new product → push to cart
                cart.push({
                    id: String(s.id),
                    name: s.name,
                    qty: Number(s.qty),
                    price: parseFloat(s.price) || 0,
                });
            }
        });

        // NOTE: Keep sessionStorage until "submit order" is clicked,
        // so added items remain persistent across refresh
    }

    // === 4. Render cart table ===
    tbody.innerHTML = "";
    let total = 0;

    cart.forEach((item) => {
        const subtotal = item.qty * item.price;
        total += subtotal;

        const row = document.createElement("tr");
        row.dataset.id = item.id;
        row.innerHTML = `
            <td>${item.name}</td>
            <td class="qty">${item.qty}</td>
            <td class="price">₱${item.price.toFixed(2)}</td>
            <td class="subtotal">₱${subtotal.toFixed(2)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-warning btn-sm editQty" data-id="${
                    item.id
                }">Edit</button>
                <button type="button" class="btn btn-danger btn-sm deleteItem" data-id="${
                    item.id
                }">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    // set total price + update hidden input
    totalPriceEl.textContent = total.toFixed(2);
    cartInput.value = JSON.stringify(cart);

    // === 5. Helper: recalc totals after edit/delete ===
    function recalc() {
        let newTotal = 0;
        cart.forEach((i) => (newTotal += i.qty * i.price));
        totalPriceEl.textContent = newTotal.toFixed(2);
        cartInput.value = JSON.stringify(cart);
    }

    // === 6. State variables for modals ===
    let deleteId = null;
    let editId = null;

    // Bootstrap modal references
    const deleteModalEl = document.getElementById("deleteConfirmModal");
    const deleteModal = new bootstrap.Modal(deleteModalEl);

    const editModalEl = document.getElementById("editQtyModal");
    const editModal = new bootstrap.Modal(editModalEl);

    // === 7. Event: open modals (delete/edit) ===
    tbody.addEventListener("click", function (e) {
        if (e.target.classList.contains("deleteItem")) {
            // prepare delete
            deleteId = e.target.dataset.id;
            const productName = e.target
                .closest("tr")
                ?.querySelector("td:first-child")
                ?.textContent.trim();
            document.getElementById("deleteItemName").textContent = productName;
            deleteModal.show();
        }

        if (e.target.classList.contains("editQty")) {
            // prepare edit
            editId = e.target.dataset.id;
            const row = e.target.closest("tr");
            const productName = row
                ?.querySelector("td:first-child")
                ?.textContent.trim();
            const currentQty = row?.querySelector(".qty")?.textContent.trim();

            document.getElementById("editItemName").textContent = productName;
            document.getElementById("editQtyInput").value = currentQty || 1;

            editModal.show();
        }
    });

    // === 8. Confirm Delete ===
    document
        .getElementById("confirmDeleteBtn")
        .addEventListener("click", () => {
            if (!deleteId) return;

            // remove from cart
            cart = cart.filter((c) => String(c.id) !== String(deleteId));
            sessionStorage.setItem("cart", JSON.stringify(cart));

            // remove from DOM
            const row = tbody.querySelector(`tr[data-id="${deleteId}"]`);
            if (row) row.remove();

            recalc();
            deleteId = null;

            deleteModal.hide(); // close modal
        });

    // === 9. Confirm Edit ===
    document.getElementById("confirmEditBtn").addEventListener("click", () => {
        const newQty = parseInt(
            document.getElementById("editQtyInput").value,
            10
        );
        if (!editId || isNaN(newQty) || newQty <= 0) return;

        const item = cart.find((c) => String(c.id) === String(editId));
        if (!item) return;

        // update qty in cart + session
        item.qty = newQty;
        sessionStorage.setItem("cart", JSON.stringify(cart));

        // update DOM row
        const row = tbody.querySelector(`tr[data-id="${editId}"]`);
        if (row) {
            row.querySelector(".qty").textContent = item.qty;
            row.querySelector(".subtotal").textContent =
                "₱" + (item.qty * item.price).toFixed(2);
        }

        recalc();
        editId = null;

        editModal.hide(); // close modal
    });

    // === 10. Initialize all Bootstrap popovers (info icons, etc.) ===
    const popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});
