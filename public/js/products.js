document.addEventListener("DOMContentLoaded", function () {
    // ===============================
    // 1. Handle quantity increment (+)
    // ===============================
    document.querySelectorAll(".increaseQty").forEach((btn) => {
        btn.addEventListener("click", function () {
            // Find the related input field for this product
            let input = document.querySelector(
                `.productQty[data-id='${this.dataset.id}']`
            );
            // Increase quantity by 1
            input.value = parseInt(input.value) + 1;
        });
    });

    // ===============================
    // 2. Handle quantity decrement (-)
    // ===============================
    document.querySelectorAll(".decreaseQty").forEach((btn) => {
        btn.addEventListener("click", function () {
            // Find the related input field for this product
            let input = document.querySelector(
                `.productQty[data-id='${this.dataset.id}']`
            );
            // Decrease quantity only if > 0 (prevent negative values)
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    // ===============================
    // 3. Add selected products to cart
    // ===============================
    const addToCartBtn = document.getElementById("addToCartBtn");
    addToCartBtn?.addEventListener("click", function () {
        let selected = [];

        // Collect all product qty inputs from the page
        document.querySelectorAll(".productQty").forEach((input) => {
            let qty = parseInt(input.value);
            if (qty > 0) {
                // Parse raw price (in case formatted with commas)
                const rawPrice = String(input.dataset.price ?? "");
                const numericPrice =
                    parseFloat(rawPrice.replace(/,/g, "")) || 0;

                // Push product data into selected array
                selected.push({
                    id: String(input.dataset.id),
                    name: input.dataset.name,
                    price: numericPrice,
                    qty: qty,
                });
            }
        });

        // If nothing selected → show warning modal
        if (selected.length === 0) {
            new bootstrap.Modal(
                document.getElementById("selectProductModal")
            ).show();
            return;
        }

        // ===============================
        // 4. Merge with existing session cart
        // ===============================
        let existing = [];
        try {
            existing = JSON.parse(sessionStorage.getItem("cart") || "[]");
        } catch (e) {
            existing = [];
        }

        // Merge logic → update qty if product exists, else add new
        selected.forEach((s) => {
            const found = existing.find((c) => String(c.id) === String(s.id));
            if (found) {
                found.qty += s.qty; // ✅ add qty if same product
            } else {
                existing.push(s); // ✅ push as new product
            }
        });

        // Save back merged cart into sessionStorage
        sessionStorage.setItem("cart", JSON.stringify(existing));

        // ===============================
        // 5. Clear merge flags (avoid re-merging on order page)
        // ===============================
        Object.keys(sessionStorage).forEach((k) => {
            if (k.startsWith("mergedFor_")) {
                sessionStorage.removeItem(k);
            }
        });

        // ===============================
        // 6. Redirect to order page
        // ===============================
        window.location.href = window.orderIndexUrl;
    });
});
