document.addEventListener("DOMContentLoaded", function () {
    // Target both create & edit product_name inputs
    const productNames = document.querySelectorAll(
        "#product_name, #product_nameEdit"
    );
    productNames.forEach((input) => {
        input.addEventListener("input", function () {
            let words = this.value.trim().split(/\s+/).filter(Boolean);
            if (words.length > 10) {
                this.value = words.slice(0, 10).join(" ");
            }
        });
    });

    // Target both create & edit textareas
    const descriptions = document.querySelectorAll(
        "#floatingTextarea, #floatingTextareaEdit"
    );
    descriptions.forEach((textarea) => {
        textarea.addEventListener("input", function () {
            let words = this.value.trim().split(/\s+/).filter(Boolean);
            if (words.length > 30) {
                this.value = words.slice(0, 30).join(" ");
            }
        });
    });
});

document.querySelectorAll("#price, #priceEdit").forEach((priceInput) => {
    priceInput.addEventListener("input", function () {
        let value = this.value;

        // ✅ Only allow digits + one decimal point
        value = value.replace(/[^0-9.]/g, "");
        let parts = value.split(".");

        // ✅ Only one decimal allowed
        if (parts.length > 2) {
            parts = [parts[0], parts[1]];
        }

        // ✅ Limit integer part to 6 digits
        if (parts[0].length > 6) {
            parts[0] = parts[0].slice(0, 6);
        }

        // ✅ Limit decimal part to 2 digits
        if (parts[1]) {
            parts[1] = parts[1].slice(0, 2);
        }

        // ✅ Rebuild value
        value = parts.join(".");

        // ✅ Ensure total digits (before + after) ≤ 8
        let digitsOnly = value.replace(".", "");
        if (digitsOnly.length > 8) {
            // Cut extra digits
            digitsOnly = digitsOnly.slice(0, 8);
            if (parts.length === 2) {
                value =
                    digitsOnly.slice(0, digitsOnly.length - 2) +
                    "." +
                    digitsOnly.slice(-2);
            } else {
                value = digitsOnly;
            }
        }

        this.value = value;
    });
});
