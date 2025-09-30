document.addEventListener("DOMContentLoaded", function () {
    // Quantity increment
    document.querySelectorAll(".increaseQty").forEach((btn) => {
        btn.addEventListener("click", function () {
            let input = document.querySelector(
                `.productQty[data-id='${this.dataset.id}']`
            );
            input.value = parseInt(input.value) + 1;
        });
    });

    // Quantity decrement
    document.querySelectorAll(".decreaseQty").forEach((btn) => {
        btn.addEventListener("click", function () {
            let input = document.querySelector(
                `.productQty[data-id='${this.dataset.id}']`
            );
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    // Add to cart
    const addToCartBtn = document.getElementById("addToCartBtn");
    addToCartBtn?.addEventListener("click", function () {
        let selected = [];

        document.querySelectorAll(".productQty").forEach((input) => {
            let qty = parseInt(input.value);
            if (qty > 0) {
                const rawPrice = String(input.dataset.price ?? "");
                const numericPrice =
                    parseFloat(rawPrice.replace(/,/g, "")) || 0;

                selected.push({
                    id: String(input.dataset.id),
                    name: input.dataset.name,
                    price: numericPrice,
                    qty: qty,
                });
            }
        });

        if (selected.length === 0) {
            new bootstrap.Modal(
                document.getElementById("selectProductModal")
            ).show();
            return;
        }

        // Save to session
        // sessionStorage.setItem("cart", JSON.stringify(selected));
        // Merge with existing session cart
        let existing = [];
        try {
            existing = JSON.parse(sessionStorage.getItem("cart") || "[]");
        } catch (e) {
            existing = [];
        }

        selected.forEach((s) => {
            const found = existing.find((c) => String(c.id) === String(s.id));
            if (found) {
                found.qty += s.qty; // ✅ increase qty if already exists
            } else {
                existing.push(s); // ✅ add as new product
            }
        });

        // Save back merged cart
        sessionStorage.setItem("cart", JSON.stringify(existing));

        // Clear merge flags
        Object.keys(sessionStorage).forEach((k) => {
            if (k.startsWith("mergedFor_")) {
                sessionStorage.removeItem(k);
            }
        });

        // Redirect to cart
        window.location.href = window.orderIndexUrl;
    });
});
