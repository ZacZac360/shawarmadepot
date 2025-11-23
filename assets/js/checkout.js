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
    const HARD_CODED_OTP   = "000000";

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
    const hiddenCustomerName     = document.getElementById("hiddenCustomerName");
    const hiddenCustomerPhone    = document.getElementById("hiddenCustomerPhone");
    const hiddenCustomerMessenger= document.getElementById("hiddenCustomerMessenger");
    const hiddenCustomerEmail    = document.getElementById("hiddenCustomerEmail");

    const hiddenFulfillmentMode  = document.getElementById("hiddenFulfillmentMode");
    const hiddenSubdivision      = document.getElementById("hiddenSubdivision");
    const hiddenDeliveryAddress  = document.getElementById("hiddenDeliveryAddress");
    const hiddenDeliveryLandmark = document.getElementById("hiddenDeliveryLandmark");

    const hiddenSubtotal         = document.getElementById("hiddenSubtotal");
    const hiddenDeliveryFee      = document.getElementById("hiddenDeliveryFee");
    const hiddenTotalAmount      = document.getElementById("hiddenTotalAmount");

    const hiddenCartJson         = document.getElementById("hiddenCartJson");

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
        phone: ""
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
    if (hiddenCustomerName)      hiddenCustomerName.value     = details.customerName || "";
    if (hiddenCustomerPhone)     hiddenCustomerPhone.value    = details.phone || "";
    if (hiddenCustomerMessenger) hiddenCustomerMessenger.value= details.messenger || "";
    if (hiddenCustomerEmail)     hiddenCustomerEmail.value    = details.email || "";

    if (hiddenFulfillmentMode)   hiddenFulfillmentMode.value  = details.fulfillmentMode || "";
    if (hiddenSubdivision)       hiddenSubdivision.value      = details.subdivision || "";

    // For address, we stored blockLot + landmark in details
    if (hiddenDeliveryAddress)   hiddenDeliveryAddress.value  = details.blockLot || "";
    if (hiddenDeliveryLandmark)  hiddenDeliveryLandmark.value = details.landmark || "";


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
    // Make sure we have the latest details from localStorage, just in case
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
    if (hiddenCustomerEmail)      hiddenCustomerEmail.value     = details.email || "";

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
    let subtotal = 0;
    if (Array.isArray(cart)) {
        cart.forEach(item => {
            subtotal += (item.unitPrice || 0) * (item.qty || 0);
        });
    }

    const mode        = details.fulfillmentMode || "pickup";
    const subdivision = details.subdivision || "";
    let deliveryFee   = 0;

    if (mode === "delivery") {
        const zone = DELIVERY_ZONES[subdivision];
        if (zone) deliveryFee = zone.fee + zone.gate;
    }

    const total = subtotal + deliveryFee;

    if (hiddenSubtotal)     hiddenSubtotal.value     = subtotal.toFixed(2);
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

        // Show only for real delivery with a fee
        if (deliveryRow) {
            const shouldShowDeliveryRow =
                mode === "delivery" && subdivision && effDeliveryFee > 0;

            if (shouldShowDeliveryRow) {
                deliveryRow.style.setProperty("display", "flex", "important");
            } else {
                deliveryRow.style.setProperty("display", "none", "important");
            }
        }

        // Label text
        if (mode === "delivery" && effDeliveryFee > 0) {
            feeLabel.textContent = formatPeso(effDeliveryFee);
        } else {
            feeLabel.textContent = "Calculated by area";
        }

        const total = subtotal + effDeliveryFee;
        totalEl.textContent = formatPeso(total);

        // 🔥 Push values into hidden money + mode/subdivision
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

    // ---------- STEP 1 → STEP 2 ----------
    if (btnConfirmStep1 && step1Box && step2Box) {
        btnConfirmStep1.addEventListener("click", function () {
            // Just toggle visibility
            step1Box.style.display = "none";
            step2Box.style.display = "block";

            if (otpCodeInput) {
                otpCodeInput.value = "";
                otpCodeInput.classList.remove("is-invalid");
                otpCodeInput.focus();
            }
        });
    }

    if (btnBackStep2 && step1Box && step2Box) {
        btnBackStep2.addEventListener("click", function () {
            step2Box.style.display = "none";
            step1Box.style.display = "block";
        });
    }

    // ---------- OTP CHECK + FINAL SUBMIT ----------
    if (btnPlaceOrder && checkoutForm) {
        btnPlaceOrder.addEventListener("click", function () {
        if (!otpCodeInput) return;

        const otpValue = otpCodeInput.value.trim();
        if (otpValue !== HARD_CODED_OTP) {
            otpCodeInput.classList.add("is-invalid");
            if (otpError) {
                otpError.textContent = "Incorrect code. For testing, use 000000.";
            }
            return;
        }

        otpCodeInput.classList.remove("is-invalid");
        if (otpError) otpError.textContent = "";

        // IMPORTANT: sync everything into hidden inputs JUST BEFORE submit
        syncHiddenFields();

        // ✅ Submit as POST to order-confirmed.php
        checkoutForm.submit();
    });
}


    console.log("Loaded order details:", details);
    console.log("Loaded cart:", cart);

});
