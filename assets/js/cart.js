// cart.js
document.addEventListener("DOMContentLoaded", function () {
    // ---------------------- Cart state & DOM refs ----------------------
    const cart = []; // { key, name, summary, unitPrice, qty }

    // Store delivery info here so order-details.js can sync
    let fulfillmentMode = "";     // "", "delivery", "pickup"
    let deliveryFee = 0;
    let hasDeliveryZone = false;
    // Tracks if user has tried to proceed at least once
    let hasAttemptedCheckout = false;


    const cartItemsEl           = document.getElementById("cartItems");
    const cartSubtotalEl        = document.getElementById("cartSubtotal");
    const cartDeliveryFeeEl     = document.getElementById("cartDeliveryFee");
    const cartTotalEl           = document.getElementById("cartTotal");
    const cartItemCountEl       = document.getElementById("cartItemCount");
    const cartFulfillmentNoteEl = document.getElementById("cartFulfillmentNote");
    const cartDeliveryRowEl     = document.getElementById("cartDeliveryRow");
    const proceedBtn = document.getElementById("btnProceedCheckout");
    const errorBox   = document.getElementById("menuCheckoutErrors");

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

            // Revalidate so "empty cart" error shows up again after removing last item
            if (typeof validateCheckout === "function") {
                validateCheckout({ showErrors: hasAttemptedCheckout, allowRedirect: false });
            }

            return; // <- important: end function here when cart is empty
        }

        // Non-empty cart branch
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

        // Auto-validate after each cart change, but only *show* errors
        // after they’ve tried to proceed at least once.
        if (typeof validateCheckout === "function") {
            validateCheckout({ showErrors: hasAttemptedCheckout, allowRedirect: false });
        }
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

    // ---------------------- Proceed to Checkout validation ----------------------


    function clearFieldError(el) {
        if (!el) return;
        el.classList.remove("is-invalid");
    }

    function setFieldError(el) {
        if (!el) return;
        el.classList.add("is-invalid");
        if (el.animate) {
            el.animate(
                [
                    { transform: "translateX(0)" },
                    { transform: "translateX(-3px)" },
                    { transform: "translateX(3px)" },
                    { transform: "translateX(0)" }
                ],
                { duration: 180, iterations: 2 }
            );
        }
    }

    function validateCheckout({ showErrors = true, allowRedirect = false } = {}) {
        if (errorBox && showErrors) {
            errorBox.classList.add("d-none");
            errorBox.innerHTML = "";
        }

        const radioDelivery = document.getElementById("fulfillmentDelivery");
        const radioPickup   = document.getElementById("fulfillmentPickup");

        const subdivision   = document.getElementById("subdivisionSelect");
        const addrBlkLot    = document.getElementById("addrBlkLot");
        const addrLandmark  = document.getElementById("addrLandmark");
        const addrName      = document.getElementById("addrName");
        const addrPhone     = document.getElementById("addrPhone");

        const pickupName    = document.getElementById("pickupName");
        const pickupPhone   = document.getElementById("pickupPhone");

        // Clear old highlights
        [
            subdivision, addrBlkLot, addrLandmark, addrName, addrPhone,
            pickupName, pickupPhone
        ].forEach(clearFieldError);

        const errors = [];

        // Cart must not be empty
        if (!cart.length) {
            errors.push("Your cart is empty. Please add at least one item.");
        }

        // Fulfillment mode required
        const mode =
            (radioDelivery && radioDelivery.checked) ? "delivery" :
            (radioPickup && radioPickup.checked)     ? "pickup"   : "";

        if (!mode) {
            errors.push("Please choose whether this is for Delivery or Pickup.");
        }

        // Mode-specific required fields
        if (mode === "delivery") {
            if (!subdivision || !subdivision.value.trim()) {
                errors.push("Please select your subdivision.");
                setFieldError(subdivision);
            }
            if (!addrBlkLot || !addrBlkLot.value.trim()) {
                errors.push("Please enter your Block & Lot.");
                setFieldError(addrBlkLot);
            }
            if (!addrLandmark || !addrLandmark.value.trim()) {
                errors.push("Please enter a landmark so the rider can find you.");
                setFieldError(addrLandmark);
            }
            if (!addrName || !addrName.value.trim()) {
                errors.push("Please enter your name for delivery.");
                setFieldError(addrName);
            }
            if (!addrPhone || !addrPhone.value.trim()) {
                errors.push("Please enter your contact number for delivery.");
                setFieldError(addrPhone);
            }

            // Make sure fee was computed for a valid zone
            if (window.CartState) {
                const st = window.CartState.getFulfillmentState();
                if (!st.hasZone) {
                    errors.push("Please choose a valid subdivision so we can compute your delivery fee.");
                    setFieldError(subdivision);
                }
            }

        } else if (mode === "pickup") {
            if (!pickupName || !pickupName.value.trim()) {
                errors.push("Please enter your name for pickup.");
                setFieldError(pickupName);
            }
            if (!pickupPhone || !pickupPhone.value.trim()) {
                errors.push("Please enter your contact number for pickup.");
                setFieldError(pickupPhone);
            }
        }

        // reCAPTCHA (only enforce when actually trying to go to checkout)
        if (allowRedirect) {
            try {
                if (typeof grecaptcha !== "undefined") {
                    const captchaResponse = grecaptcha.getResponse();
                    if (!captchaResponse) {
                        errors.push("Please verify you're not a robot before proceeding.");
                    }
                } else {
                    console.warn("reCAPTCHA not loaded; skipping captcha check.");
                }
            } catch (e) {
                console.error("Error while checking reCAPTCHA", e);
            }
        }

        // If there are errors, show them and block redirect
        if (errors.length) {
            if (errorBox && showErrors) {
                errorBox.innerHTML =
                    "<ul class='mb-0'>" +
                    errors.map(e => `<li>${e}</li>`).join("") +
                    "</ul>";
                errorBox.classList.remove("d-none");
            }
            return false;
        }

        // No errors: clear box
        if (errorBox && showErrors) {
            errorBox.classList.add("d-none");
            errorBox.innerHTML = "";
        }

        if (allowRedirect) {
            window.location.href = "checkout.php";
        }
        return true;
    }


    // Button click => validate + redirect if OK
    if (proceedBtn) {
        proceedBtn.addEventListener("click", () => {
            hasAttemptedCheckout = true;
            const valid = validateCheckout({
                showErrors: true,
                allowRedirect: false
            })
            if (valid) {
                // Open CAPTCHA modal instead of redirecting
                const captchaModal = new bootstrap.Modal(document.getElementById("captchaModal"));
                captchaModal.show();
            }
        });
    }

    // Live-update errors when the user fixes fields
    const reactiveInputs = [
        document.getElementById("fulfillmentDelivery"),
        document.getElementById("fulfillmentPickup"),
        document.getElementById("subdivisionSelect"),
        document.getElementById("addrBlkLot"),
        document.getElementById("addrLandmark"),
        document.getElementById("addrName"),
        document.getElementById("addrPhone"),
        document.getElementById("pickupName"),
        document.getElementById("pickupPhone")
    ];

    reactiveInputs.forEach((el) => {
        if (!el) return;
        const evt = (el.type === "radio" || el.tagName === "SELECT") ? "change" : "input";
        el.addEventListener(evt, () => {
            // Only show errors live after they've tried once
            validateCheckout({ showErrors: hasAttemptedCheckout, allowRedirect: false });
        });
    });

    const captchaConfirmBtn = document.getElementById("captchaConfirmBtn");
    const captchaModalError = document.getElementById("captchaModalError");

    if (captchaConfirmBtn) {
        captchaConfirmBtn.addEventListener("click", () => {

            const captchaResponse = grecaptcha.getResponse();

            if (!captchaResponse) {
                captchaModalError.classList.remove("d-none");
                captchaModalError.textContent = "Please verify the CAPTCHA.";
                return;
            }

            // CAPTCHA ok → redirect now
            window.location.href = "checkout.php";
        });
    }



});
