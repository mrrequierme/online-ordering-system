document.addEventListener("DOMContentLoaded", function () {
    // run early inside DOMContentLoaded
    if (window.orderSubmitted || document.querySelector(".alert-success")) {
        sessionStorage.removeItem("cart");
        // optional: clear merged flags too
        Object.keys(sessionStorage).forEach((k) => {
            if (k.startsWith("mergedFor_")) sessionStorage.removeItem(k);
        });
    }

    const tbody = document.querySelector("#orderTable tbody");
    const totalPriceEl = document.getElementById("totalPrice");
    const cartInput = document.getElementById("cartInput");

    function parsePrice(text) {
        return parseFloat((text || "").replace(/[^\d.-]/g, "")) || 0;
    }

    let cart = [];

    // === CASE 1: No pending order OR status != pending ===
    if (!window.pendingOrderId || window.pendingOrderStatus !== "pending") {
        try {
            cart = JSON.parse(sessionStorage.getItem("cart") || "[]");
        } catch (e) {
            cart = [];
        }
    } else if (
        window.pendingOrderId &&
        window.pendingOrderStatus === "pending"
    ) {
        // Load DB cart first
        tbody.querySelectorAll("tr").forEach((row) => {
            cart.push({
                id: String(row.dataset.id),
                name: row.querySelector("td:first-child").innerText.trim(),
                qty: parseInt(row.querySelector(".qty").innerText) || 0,
                price: parsePrice(row.querySelector(".price").innerText),
            });
        });

        // Merge sessionStorage items into DB cart
        let stored = [];
        try {
            stored = JSON.parse(sessionStorage.getItem("cart") || "[]");
        } catch (e) {}

        stored.forEach((s) => {
            const existing = cart.find((c) => String(c.id) === String(s.id));
            if (existing) {
                existing.qty += Number(s.qty); // ✅ increase qty if same product
            } else {
                cart.push({
                    id: String(s.id),
                    name: s.name,
                    qty: Number(s.qty),
                    price: parseFloat(s.price) || 0,
                });
            }
        });

        // Keep sessionStorage until submit → so added items persist
    }

    // === Render table ===
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

    totalPriceEl.textContent = total.toFixed(2);
    cartInput.value = JSON.stringify(cart);

    // === Helper: recalc total & hidden input ===
    function recalc() {
        let newTotal = 0;
        cart.forEach((i) => (newTotal += i.qty * i.price));
        totalPriceEl.textContent = newTotal.toFixed(2);
        cartInput.value = JSON.stringify(cart);
    }

    let deleteId = null;
    let editId = null;

    // DELETE BUTTON CLICK → modal
    // keep references outside so we can hide later
    const deleteModalEl = document.getElementById("deleteConfirmModal");
    const deleteModal = new bootstrap.Modal(deleteModalEl);

    const editModalEl = document.getElementById("editQtyModal");
    const editModal = new bootstrap.Modal(editModalEl);

    // DELETE BUTTON CLICK → show modal
    tbody.addEventListener("click", function (e) {
        if (e.target.classList.contains("deleteItem")) {
            deleteId = e.target.dataset.id;
            const productName = e.target
                .closest("tr")
                ?.querySelector("td:first-child")
                ?.textContent.trim();
            document.getElementById("deleteItemName").textContent = productName;
            deleteModal.show();
        }

        if (e.target.classList.contains("editQty")) {
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

    // CONFIRM DELETE
    document
        .getElementById("confirmDeleteBtn")
        .addEventListener("click", () => {
            if (!deleteId) return;

            cart = cart.filter((c) => String(c.id) !== String(deleteId));
            sessionStorage.setItem("cart", JSON.stringify(cart));

            const row = tbody.querySelector(`tr[data-id="${deleteId}"]`);
            if (row) row.remove();

            recalc();
            deleteId = null;

            // ✅ hide modal
            deleteModal.hide();
        });

    // CONFIRM EDIT
    document.getElementById("confirmEditBtn").addEventListener("click", () => {
        const newQty = parseInt(
            document.getElementById("editQtyInput").value,
            10
        );
        if (!editId || isNaN(newQty) || newQty <= 0) return;

        const item = cart.find((c) => String(c.id) === String(editId));
        if (!item) return;

        item.qty = newQty;
        sessionStorage.setItem("cart", JSON.stringify(cart));

        const row = tbody.querySelector(`tr[data-id="${editId}"]`);
        if (row) {
            row.querySelector(".qty").textContent = item.qty;
            row.querySelector(".subtotal").textContent =
                "₱" + (item.qty * item.price).toFixed(2);
        }

        recalc();
        editId = null;

        // ✅ hide modal
        editModal.hide();
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});
