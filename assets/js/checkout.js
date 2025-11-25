// checkout.js
document.addEventListener("DOMContentLoaded", function () {
    const formatPeso = (n) => "₱" + (n || 0);

    // ---------- FORM + 2-STEP ----------
    const checkoutForm     = document.getElementById("checkoutForm");
    const step1Box         = document.getElementById("checkoutStep1");
    const step2Box         = document.getElementById("checkoutStep2");
    const btnConfirmStep1  = document.getElementById("btnConfirmStep1");
    const btnBackStep2     = document.getElementById("btnBackStep2");
    const btnPlaceOrder    = document.getElementById("btnPlaceOrder");
    const otpCodeInput     = document.getElementById("otpCode");
    const otpError         = document.getElementById("otpError");
    const otpHelpText      = document.getElementById("otpHelpText");

    // Email for COD-only OTP
    const codEmailWrapper  = document.getElementById("codEmailWrapper");
    const codEmailInput    = document.getElementById("checkoutEmail");
    const codEmailError    = document.getElementById("emailError");

    // ---------- SUMMARY ELEMENTS ----------
    const detailsSummaryEl = document.getElementById("checkoutDetailsSummary");

    // Totals & cart
    const itemsContainer   = document.getElementById("checkoutCartItems");
    const subtotalEl       = document.getElementById("checkoutSubtotal");
    const feeLabel         = document.getElementById("checkoutDeliveryFeeLabel");
    const totalEl          = document.getElementById("checkoutTotal");
    const deliveryRow      = document.getElementById("checkoutDeliveryRow");

    // Fulfillment (may or may not exist on this page)
    const fulfillmentDel   = document.getElementById("fulfillmentDelivery");
    const fulfillmentPu    = document.getElementById("fulfillmentPickup");
    const subdivisionSel   = document.getElementById("subdivisionSelect");

    // ---------- HIDDEN FIELDS (for POST to PHP) ----------
    const hiddenCustomerName      = document.getElementById("hiddenCustomerName");
    const hiddenCustomerPhone     = document.getElementById("hiddenCustomerPhone");
    const hiddenCustomerMessenger = document.getElementById("hiddenCustomerMessenger");
    const hiddenCustomerEmail     = document.getElementById("hiddenCustomerEmail");

    const hiddenFulfillmentMode   = document.getElementById("hiddenFulfillmentMode");
    const hiddenSubdivision       = document.getElementById("hiddenSubdivision");
    const hiddenDeliveryAddress   = document.getElementById("hiddenDeliveryAddress");
    const hiddenDeliveryLandmark  = document.getElementById("hiddenDeliveryLandmark");

    const hiddenSubtotal          = document.getElementById("hiddenSubtotal");
    const hiddenDeliveryFee       = document.getElementById("hiddenDeliveryFee");
    const hiddenTotalAmount       = document.getElementById("hiddenTotalAmount");

    const hiddenCartJson          = document.getElementById("hiddenCartJson");

    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');

    function getSelectedPaymentMethod() {
        const checked = document.querySelector('input[name="payment_method"]:checked');
        return checked ? checked.value : "cod";
    }

    function isValidEmail(email) {
        return /\S+@\S+\.\S+/.test(email);
    }

    function updateEmailForPayment() {
        if (!codEmailWrapper || !codEmailInput) return;
        const method = getSelectedPaymentMethod();

        if (method === "cod") {
            codEmailWrapper.style.display = "block";
        } else {
            codEmailWrapper.style.display = "none";
            codEmailInput.value = "";
            codEmailInput.classList.remove("is-invalid");
            if (codEmailError) codEmailError.textContent = "Please enter a valid email address.";
        }
    }

    if (paymentMethodInputs && paymentMethodInputs.length) {
        paymentMethodInputs.forEach((inp) => {
            inp.addEventListener("change", updateEmailForPayment);
        });
    }
    updateEmailForPayment();

    // ---------- DELIVERY ZONES ----------
    const DELIVERY_ZONES = {
        classic:  { fee: 15, gate: 10 },
        heights:  { fee: 15, gate: 0 },
        central:  { fee: 15, gate: 0 },
        phase1:   { fee: 15, gate: 0 },
        phase2:   { fee: 15, gate: 0 },
        phase3:   { fee: 15, gate: 0 },
        phase4:   { fee: 15, gate: 0 },
        phase5:   { fee: 15, gate: 0 },
        north1:   { fee: 15, gate: 0 },
        north2:   { fee: 15, gate: 0 },
        south1:   { fee: 15, gate: 0 },
        south2:   { fee: 15, gate: 0 },
        ndgv:     { fee: 25, gate: 10 },
        sdgv:     { fee: 25, gate: 10 }
    };

    function calcDeliveryFee(subdivision) {
        const zone = DELIVERY_ZONES[subdivision];
        if (!zone) return 0;
        return zone.fee + zone.gate;
    }

    // ---------- LOAD STORED ORDER DETAILS ----------
    let details = {
        fulfillmentMode: "",
        subdivision: "",
        blockLot: "",
        landmark: "",
        customerName: "",
        phone: "",
        email: ""
    };

    try {
        const stored = localStorage.getItem("shawarma_order_details");
        if (stored) {
            const parsed = JSON.parse(stored) || {};
            details = Object.assign(details, parsed);
        }
    } catch (e) {
        console.error("Failed to load order details on checkout", e);
    }

    // Fill hidden customer + address fields from details
    if (hiddenCustomerName)      hiddenCustomerName.value      = details.customerName || "";
    if (hiddenCustomerPhone)     hiddenCustomerPhone.value     = details.phone || "";
    if (hiddenCustomerMessenger) hiddenCustomerMessenger.value = details.messenger || "";

    // For email, we now mainly use the checkout email field
    if (hiddenCustomerEmail)     hiddenCustomerEmail.value     = details.email || "";

    if (hiddenFulfillmentMode)   hiddenFulfillmentMode.value   = details.fulfillmentMode || "";
    if (hiddenSubdivision)       hiddenSubdivision.value       = details.subdivision || "";

    if (hiddenDeliveryAddress)   hiddenDeliveryAddress.value   = details.blockLot || "";
    if (hiddenDeliveryLandmark)  hiddenDeliveryLandmark.value  = details.landmark || "";

    // ---------- RENDER "YOUR DETAILS" SUMMARY ----------
    if (detailsSummaryEl) {
        if (!details.fulfillmentMode && !details.customerName && !details.phone) {
            detailsSummaryEl.textContent =
                "We couldn’t find your details. You may need to go back to the menu and enter them again.";
        } else {
            const modeLabel =
                details.fulfillmentMode === "delivery" ? "Delivery" :
                details.fulfillmentMode === "pickup"   ? "Pickup"   :
                "Not specified";

            let areaLabel = "";
            if (details.subdivision) {
                const friendlyNames = {
                    classic: "San Marino Classic",
                    heights: "San Marino Heights",
                    central: "San Marino Central",
                    phase1:  "San Marino Phase 1",
                    phase2:  "San Marino Phase 2",
                    phase3:  "San Marino Phase 3",
                    phase4:  "San Marino Phase 4",
                    phase5:  "San Marino Phase 5",
                    north1:  "San Marino North 1",
                    north2:  "San Marino North 2",
                    south1:  "San Marino South 1",
                    south2:  "San Marino South 2",
                    ndgv:    "North Dasma Garden Villa",
                    sdgv:    "South Dasma Garden Villa"
                };
                areaLabel = friendlyNames[details.subdivision] || details.subdivision;
            }

            let html = `
                <div><strong>${details.customerName || "Unnamed customer"}</strong></div>
                <div>Contact: ${details.phone || "No phone provided"}</div>
                <div>Mode: ${modeLabel}</div>
            `;

            if (details.fulfillmentMode === "delivery") {
                html += `
                    <div>Area: ${areaLabel || "Not specified"}</div>
                    <div>Block &amp; Lot: ${details.blockLot || "Not specified"}</div>
                    <div>Landmark: ${details.landmark || "Not specified"}</div>
                `;
            } else if (details.fulfillmentMode === "pickup") {
                html += `
                    <div>Pickup at: Shawarma Depot store (as shown on the menu page)</div>
                `;
            }

            detailsSummaryEl.innerHTML = html;
        }
    }

    // Hydrate radios / select if they exist
    if (fulfillmentDel && details.fulfillmentMode === "delivery") {
        fulfillmentDel.checked = true;
    }
    if (fulfillmentPu && details.fulfillmentMode === "pickup") {
        fulfillmentPu.checked = true;
    }
    if (subdivisionSel && details.subdivision) {
        subdivisionSel.value = details.subdivision;
    }

    // ---------- LOAD CART ----------
    let cart = [];
    try {
        const storedCart = localStorage.getItem("shawarma_cart");
        if (storedCart) {
            const parsed = JSON.parse(storedCart);
            if (Array.isArray(parsed)) {
                cart = parsed;
            }
        }
    } catch (e) {
        console.error("Failed to load cart for checkout", e);
    }

    // Save cart snapshot for PHP
    if (hiddenCartJson) {
        try {
            hiddenCartJson.value = JSON.stringify(cart);
        } catch (e) {
            hiddenCartJson.value = "[]";
        }
    }

    if (!cart.length) {
        if (itemsContainer) {
            itemsContainer.innerHTML = `
                <div class="text-muted small">
                    Your cart is empty. <a href="menu.php">Go back to the menu</a> to add items.
                </div>
            `;
        }
    } else if (itemsContainer) {
        let html = "";
        cart.forEach((item) => {
            const lineTotal = (item.unitPrice || 0) * (item.qty || 0);
            html += `
                <div class="cart-item mb-2">
                    <div class="item-header d-flex justify-content-between">
                        <span>${item.name || "Item"}</span>
                        <span class="item-price">${formatPeso(lineTotal)}</span>
                    </div>
                    <div class="item-summary small text-muted">
                        ${item.summary || ""}
                    </div>
                    <div class="qty-box">
                        <span class="small text-muted me-1">Qty:</span>
                        <span class="qty-number">${item.qty || 0}</span>
                    </div>
                </div>
            `;
        });
        itemsContainer.innerHTML = html;
    }

    // Subtotal
    let subtotal = cart.reduce((sum, item) => {
        return sum + (item.unitPrice || 0) * (item.qty || 0);
    }, 0);
    if (subtotalEl) {
        subtotalEl.textContent = formatPeso(subtotal);
    }

    function syncHiddenFields() {
        // Refresh details from localStorage just in case
        try {
            const stored = localStorage.getItem("shawarma_order_details");
            if (stored) {
                const parsed = JSON.parse(stored) || {};
                details = Object.assign(details, parsed);
            }
        } catch (e) {
            console.error("Failed to refresh details before submit", e);
        }

        // 1) Customer + address stuff
        if (hiddenCustomerName)       hiddenCustomerName.value      = details.customerName || "";
        if (hiddenCustomerPhone)      hiddenCustomerPhone.value     = details.phone || "";
        if (hiddenCustomerMessenger)  hiddenCustomerMessenger.value = details.messenger || "";

        // email: prefer the one typed here on checkout
        if (hiddenCustomerEmail) {
            if (codEmailInput && codEmailInput.value.trim()) {
                hiddenCustomerEmail.value = codEmailInput.value.trim();
            } else {
                hiddenCustomerEmail.value = details.email || "";
            }
        }

        if (hiddenFulfillmentMode)    hiddenFulfillmentMode.value   = details.fulfillmentMode || "";
        if (hiddenSubdivision)        hiddenSubdivision.value       = details.subdivision || "";

        if (hiddenDeliveryAddress)    hiddenDeliveryAddress.value   = details.blockLot || "";
        if (hiddenDeliveryLandmark)   hiddenDeliveryLandmark.value  = details.landmark || "";

        // 2) Cart snapshot
        if (hiddenCartJson) {
            try {
                hiddenCartJson.value = JSON.stringify(cart || []);
            } catch (e) {
                hiddenCartJson.value = "[]";
            }
        }

        // 3) Money
        let sub = 0;
        if (Array.isArray(cart)) {
            cart.forEach(item => {
                sub += (item.unitPrice || 0) * (item.qty || 0);
            });
        }

        const mode        = details.fulfillmentMode || "pickup";
        const subdivision = details.subdivision || "";
        let deliveryFee   = 0;

        if (mode === "delivery") {
            const zone = DELIVERY_ZONES[subdivision];
            if (zone) deliveryFee = zone.fee + zone.gate;
        }

        const total = sub + deliveryFee;

        if (hiddenSubtotal)     hiddenSubtotal.value     = sub.toFixed(2);
        if (hiddenDeliveryFee)  hiddenDeliveryFee.value  = deliveryFee.toFixed(2);
        if (hiddenTotalAmount)  hiddenTotalAmount.value  = total.toFixed(2);
    }

    // ---------- TOTALS + DELIVERY ROW ----------
    function recalcTotals() {
        if (!subtotalEl || !feeLabel || !totalEl) return;

        // Decide mode
        let mode = "";
        if (fulfillmentDel && fulfillmentDel.checked) {
            mode = "delivery";
        } else if (fulfillmentPu && fulfillmentPu.checked) {
            mode = "pickup";
        } else if (details.fulfillmentMode) {
            mode = details.fulfillmentMode;
        }

        // Subdivision
        let subdivision = "";
        if (subdivisionSel && subdivisionSel.value) {
            subdivision = subdivisionSel.value;
        } else if (details.subdivision) {
            subdivision = details.subdivision;
        }

        let effDeliveryFee = 0;
        if (mode === "delivery" && subdivision) {
            effDeliveryFee = calcDeliveryFee(subdivision);
        }

        if (deliveryRow) {
            const shouldShowDeliveryRow =
                mode === "delivery" && subdivision && effDeliveryFee > 0;

            if (shouldShowDeliveryRow) {
                deliveryRow.style.setProperty("display", "flex", "important");
            } else {
                deliveryRow.style.setProperty("display", "none", "important");
            }
        }

        if (mode === "delivery" && effDeliveryFee > 0) {
            feeLabel.textContent = formatPeso(effDeliveryFee);
        } else {
            feeLabel.textContent = "Calculated by area";
        }

        const total = subtotal + effDeliveryFee;
        totalEl.textContent = formatPeso(total);

        if (hiddenSubtotal)      hiddenSubtotal.value      = subtotal.toFixed(2);
        if (hiddenDeliveryFee)   hiddenDeliveryFee.value   = effDeliveryFee.toFixed(2);
        if (hiddenTotalAmount)   hiddenTotalAmount.value   = total.toFixed(2);

        if (hiddenFulfillmentMode) hiddenFulfillmentMode.value = mode || "";
        if (hiddenSubdivision)     hiddenSubdivision.value     = subdivision || "";
    }

    if (fulfillmentDel) fulfillmentDel.addEventListener("change", recalcTotals);
    if (fulfillmentPu)  fulfillmentPu.addEventListener("change", recalcTotals);
    if (subdivisionSel) subdivisionSel.addEventListener("change", recalcTotals);

    recalcTotals();

    // ---------- STEP 1 → (STEP 2 FOR COD) OR PAYMONGO REDIRECT ----------
    if (btnConfirmStep1 && step1Box && step2Box) {
        btnConfirmStep1.addEventListener("click", async function () {
            const method = getSelectedPaymentMethod();

            // Always sync hidden fields first
            syncHiddenFields();

            if (method === "cod") {
                // Validate email
                if (!codEmailInput) {
                    alert("Email input is missing.");
                    return;
                }
                const email = codEmailInput.value.trim();
                if (!email || !isValidEmail(email)) {
                    codEmailInput.classList.add("is-invalid");
                    if (codEmailError) {
                        codEmailError.textContent = "Please enter a valid email address.";
                    }
                    return;
                }
                codEmailInput.classList.remove("is-invalid");
                if (codEmailError) codEmailError.textContent = "Please enter a valid email address.";

                // Push email into hidden field so PHP sees it
                if (hiddenCustomerEmail) {
                    hiddenCustomerEmail.value = email;
                }

                // Call backend to send OTP email
                btnConfirmStep1.disabled = true;
                const originalLabel = btnConfirmStep1.innerHTML;
                btnConfirmStep1.innerHTML = "Sending code...";

                try {
                    const formData = new FormData();
                    formData.append("email", email);

                    const res = await fetch("api/otp/send-email-otp.php", {
                        method: "POST",
                        body: formData
                    });

                    const data = await res.json();
                    console.log("send-email-otp response:", data);

                    if (!data.success) {
                        alert(data.message || "Failed to send verification code. Please try again.");
                        return;
                    }

                    // Go to OTP step
                    step1Box.style.display = "none";
                    step2Box.style.display = "block";

                    if (otpHelpText) {
                        otpHelpText.textContent =
                            "We’ve sent a 6-digit confirmation code to " + email +
                            ". Enter it below to confirm your cash order.";
                    }

                    if (otpCodeInput) {
                        otpCodeInput.value = "";
                        otpCodeInput.classList.remove("is-invalid");
                        otpCodeInput.focus();
                    }
                } catch (err) {
                    console.error("Error calling send-email-otp:", err);
                    alert("There was a problem sending the verification code.");
                } finally {
                    btnConfirmStep1.disabled = false;
                    btnConfirmStep1.innerHTML = originalLabel;
                }

                return;
            }

            if (method === "paymongo") {
                // Online payment via PayMongo (unchanged)
                btnConfirmStep1.disabled = true;
                const originalLabel = btnConfirmStep1.innerHTML;
                btnConfirmStep1.innerHTML = "Opening payment page...";

                try {
                    const formData = new FormData(checkoutForm);

                    const res = await fetch("api/payments/paymongo-checkout.php", {
                        method: "POST",
                        body: formData
                    });

                    const data = await res.json();
                    console.log("PayMongo Checkout response:", data);

                    if (data.status === "ok" && data.checkout_url) {
                        window.location.href = data.checkout_url;
                    } else {
                        alert("Online payment failed to start. Please try again or choose Cash.");
                    }
                } catch (err) {
                    console.error("PayMongo Checkout error:", err);
                    alert("There was a problem contacting the payment service.");
                } finally {
                    btnConfirmStep1.disabled = false;
                    btnConfirmStep1.innerHTML = originalLabel;
                }

                return;
            }

            // Fallback: treat as COD if something weird happens
            step1Box.style.display = "none";
            step2Box.style.display = "block";
        });
    }

    if (btnBackStep2 && step1Box && step2Box) {
        btnBackStep2.addEventListener("click", function () {
            step2Box.style.display = "none";
            step1Box.style.display = "block";
        });
    }

    // ---------- OTP CHECK + FINAL SUBMIT (COD ONLY) ----------
    if (btnPlaceOrder && checkoutForm) {
        btnPlaceOrder.addEventListener("click", async function () {
            const method = getSelectedPaymentMethod();

            // If somehow on this screen with PayMongo selected, just submit
            if (method !== "cod") {
                checkoutForm.submit();
                return;
            }

            if (!otpCodeInput) return;

            const code = otpCodeInput.value.trim();
            if (code.length !== 6 || !/^[0-9]+$/.test(code)) {
                otpCodeInput.classList.add("is-invalid");
                if (otpError) {
                    otpError.textContent = "Please enter the 6-digit code.";
                }
                return;
            }

            const email =
                (codEmailInput && codEmailInput.value.trim())
                    ? codEmailInput.value.trim()
                    : (hiddenCustomerEmail ? hiddenCustomerEmail.value.trim() : "");

            if (!email) {
                alert("Missing email for verification.");
                return;
            }

            btnPlaceOrder.disabled = true;
            const originalText = btnPlaceOrder.innerHTML;
            btnPlaceOrder.innerHTML = "Verifying...";

            try {
                const formData = new FormData();
                formData.append("email", email);
                formData.append("code", code);

                const res = await fetch("api/otp/verify-email-otp.php", {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();
                console.log("verify-email-otp response:", data);

                if (!data.success) {
                    otpCodeInput.classList.add("is-invalid");
                    if (otpError) {
                        otpError.textContent = data.message || "Incorrect code. Please try again.";
                    }
                    return;
                }

                otpCodeInput.classList.remove("is-invalid");
                if (otpError) otpError.textContent = "";

                // Sync everything again just before submit
                syncHiddenFields();

                checkoutForm.submit();
            } catch (err) {
                console.error("Error verifying OTP:", err);
                alert("There was a problem verifying your code. Please try again.");
            } finally {
                btnPlaceOrder.disabled = false;
                btnPlaceOrder.innerHTML = originalText;
            }
        });
    }

    console.log("Loaded order details:", details);
    console.log("Loaded cart:", cart);
});
