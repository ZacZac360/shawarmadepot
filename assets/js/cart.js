// cart.js
document.addEventListener("DOMContentLoaded", function () {
    // ---------------------- Cart state & DOM refs ----------------------
    const cart = []; // { key, name, summary, unitPrice, qty }

    // Store delivery info here so order-details.js can sync
    let fulfillmentMode = "";     // "", "delivery", "pickup"
    let deliveryFee = 0;
    let hasDeliveryZone = false;

    const cartItemsEl           = document.getElementById("cartItems");
    const cartSubtotalEl        = document.getElementById("cartSubtotal");
    const cartDeliveryFeeEl     = document.getElementById("cartDeliveryFee");
    const cartTotalEl           = document.getElementById("cartTotal");
    const cartItemCountEl       = document.getElementById("cartItemCount");
    const cartFulfillmentNoteEl = document.getElementById("cartFulfillmentNote");
    const cartDeliveryRowEl     = document.getElementById("cartDeliveryRow");

    if (cartDeliveryRowEl) {
        cartDeliveryRowEl.style.setProperty("display", "none", "important");
    }

    const formatPeso = (n) => "₱" + (n || 0);

    // ---------------------- LocalStorage helpers ----------------------
    function saveCartToLocalStorage() {
        try {
            localStorage.setItem("shawarma_cart", JSON.stringify(cart));
        } catch (e) {
            console.error("Failed to save cart to localStorage", e);
        }
    }

    function loadCartFromLocalStorage() {
        try {
            const stored = localStorage.getItem("shawarma_cart");
            if (stored) {
                const parsed = JSON.parse(stored);
                if (Array.isArray(parsed)) {
                    cart.length = 0;
                    parsed.forEach((item) => cart.push(item));
                }
            }
        } catch (e) {
            console.error("Failed to load cart from localStorage", e);
        }
    }

    // ---------------------- Cart totals ----------------------
    function updateCartTotals() {
        if (!cartSubtotalEl || !cartDeliveryFeeEl || !cartTotalEl) return;

        const subtotal = cart.reduce((sum, item) => sum + item.unitPrice * item.qty, 0);

        const showDeliveryFee =
            fulfillmentMode === "delivery" && hasDeliveryZone;

        const effectiveDeliveryFee = showDeliveryFee ? deliveryFee : 0;

        if (cartDeliveryRowEl) {
            if (showDeliveryFee) {
                cartDeliveryRowEl.style.setProperty("display", "flex", "important");
            } else {
                cartDeliveryRowEl.style.setProperty("display", "none", "important");
            }
        }

        cartSubtotalEl.textContent    = formatPeso(subtotal);
        cartDeliveryFeeEl.textContent = formatPeso(effectiveDeliveryFee);
        cartTotalEl.textContent       = formatPeso(subtotal + effectiveDeliveryFee);
    }

    // ---------------------- Render cart ----------------------
    function renderCart() {
        if (!cartItemsEl) return;

        const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
        if (cartItemCountEl) {
            cartItemCountEl.textContent = totalQty;
        }

        if (!cart.length) {
            cartItemsEl.classList.add("text-muted");
            cartItemsEl.innerHTML = "No items yet. Come try out what we have!";
            updateCartTotals();
            return;
        }

        cartItemsEl.classList.remove("text-muted");

        let html = "";
        cart.forEach((item, idx) => {
            const lineTotal = item.unitPrice * item.qty;
            html += `
                <div class="cart-item mb-2" data-cart-index="${idx}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="fw-semibold">${item.name}</div>
                        <div class="small text-warning fw-bold">${formatPeso(lineTotal)}</div>
                    </div>
                    <div class="small text-muted mb-2">${item.summary}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-dark js-cart-dec">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span class="badge bg-warning text-dark fs-6 px-3">
                                ${item.qty}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-dark js-cart-inc">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="small text-muted">
                            ${formatPeso(item.unitPrice)} each
                        </div>
                    </div>
                </div>
            `;
        });

        cartItemsEl.innerHTML = html;

        // qty + / - buttons
        cartItemsEl.querySelectorAll(".js-cart-inc").forEach((btn) => {
            btn.addEventListener("click", () => {
                const idx = parseInt(
                    btn.closest("[data-cart-index]").dataset.cartIndex,
                    10
                );
                cart[idx].qty += 1;
                renderCart();
                saveCartToLocalStorage();
            });
        });

        cartItemsEl.querySelectorAll(".js-cart-dec").forEach((btn) => {
            btn.addEventListener("click", () => {
                const idx = parseInt(
                    btn.closest("[data-cart-index]").dataset.cartIndex,
                    10
                );
                cart[idx].qty -= 1;
                if (cart[idx].qty <= 0) {
                    cart.splice(idx, 1);
                }
                renderCart();
                saveCartToLocalStorage();
            });
        });

        updateCartTotals();
    }

    // ---------------------- Add to cart helper ----------------------
    function addToCartFromConfig(conf) {
        if (!conf || !conf.basePrice) return;

        const unitPrice = conf.basePrice + (conf.extrasTotal || 0);

        const keyParts = [
            conf.productKey,
            conf.baseLabel || "",
            conf.meatLabel || "",
            conf.sizeLabel || "",
            conf.spicy ? "spicy" : "not-spicy",
            (conf.extrasIds || []).join(",")
        ];
        const key = keyParts.join("|");

        const existing = cart.find((item) => item.key === key);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                key,
                name: conf.productName,
                summary: conf.summaryText,
                unitPrice,
                qty: 1
            });
        }

        renderCart();
        saveCartToLocalStorage();
    }

    // ---------------------- Fulfillment state exposed for order-details.js ----------------------
    function setFulfillmentState({ mode, fee, hasZone }) {
        fulfillmentMode = mode || "";
        deliveryFee     = typeof fee === "number" ? fee : 0;
        hasDeliveryZone = !!hasZone;
        updateCartTotals();
        if (cartFulfillmentNoteEl) {
            if (mode === "pickup") {
                cartFulfillmentNoteEl.textContent = "Pickup selected – delivery fee is ₱0.";
            } else if (mode === "delivery") {
                cartFulfillmentNoteEl.textContent =
                    "Delivery selected – delivery fee depends on your subdivision.";
            } else {
                cartFulfillmentNoteEl.textContent = "Choose Delivery or Pickup above.";
            }
        }
    }

    function getFulfillmentState() {
        return { mode: fulfillmentMode, fee: deliveryFee, hasZone: hasDeliveryZone };
    }

    // ---------------------- Init ----------------------
    loadCartFromLocalStorage();
    renderCart();

    // expose globally
    window.CartAPI = {
        cart,
        renderCart,
        saveCartToLocalStorage,
        loadCartFromLocalStorage,
        addToCartFromConfig
    };

    window.CartState = {
        setFulfillmentState,
        getFulfillmentState
    };

    // a shorthand for other scripts
    window.updateCartTotals = updateCartTotals;
    window.addToCartFromConfig = addToCartFromConfig;
});
