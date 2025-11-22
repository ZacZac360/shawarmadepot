// order-details.js
document.addEventListener("DOMContentLoaded", function () {
    const deliveryRadio         = document.getElementById("fulfillmentDelivery");
    const pickupRadio           = document.getElementById("fulfillmentPickup");
    const deliveryDetailsFields = document.getElementById("deliveryDetailsFields");
    const pickupDetailsFields   = document.getElementById("pickupDetailsFields"); // if you add this section
    const subdivisionSelect     = document.getElementById("subdivisionSelect");
    const addressFields         = document.getElementById("addressFields");
    const deliveryFeeMessage    = document.getElementById("deliveryFeeMessage");

    const addrBlkLot   = document.getElementById("addrBlkLot");
    const addrLandmark = document.getElementById("addrLandmark");
    const addrName     = document.getElementById("addrName");
    const addrPhone    = document.getElementById("addrPhone");

    const pickupName   = document.getElementById("pickupName");
    const pickupPhone  = document.getElementById("pickupPhone");

    // If there is no delivery/pickup UI, bail (e.g. pages that don't have that box)
    if (!deliveryRadio && !pickupRadio) return;

    const STORAGE_KEY = "shawarma_order_details";

    const DELIVERY_ZONES = {
        classic:  { fee: 15, gate: 15 },
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

    let orderDetails = {
        fulfillmentMode: "",  // "delivery" | "pickup" | ""
        subdivision: "",
        blockLot: "",
        landmark: "",
        customerName: "",
        phone: ""
    };

    let hasDeliveryZone = false;
    let deliveryFee     = 0;

    function saveOrderDetails() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(orderDetails));
        } catch (e) {
            console.error("Failed to save order details", e);
        }
    }

    function loadOrderDetails() {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored) {
                const parsed = JSON.parse(stored);
                if (parsed && typeof parsed === "object") {
                    orderDetails = Object.assign(orderDetails, parsed);
                }
            }
        } catch (e) {
            console.error("Failed to load order details", e);
        }
    }

    function syncCartState() {
        if (!window.CartState || typeof window.CartState.setFulfillmentState !== "function") return;
        window.CartState.setFulfillmentState({
            mode: orderDetails.fulfillmentMode,
            fee: deliveryFee,
            hasZone: hasDeliveryZone
        });
    }

    function updateFulfillmentUI() {
        const mode = orderDetails.fulfillmentMode || "";

        if (mode === "delivery") {
            if (deliveryDetailsFields) deliveryDetailsFields.style.display = "block";
            if (pickupDetailsFields)   pickupDetailsFields.style.display   = "none";
            if (addressFields && hasDeliveryZone) {
                addressFields.style.display = "block";
            }
        } else if (mode === "pickup") {
            if (deliveryDetailsFields) deliveryDetailsFields.style.display = "none";
            if (pickupDetailsFields)   pickupDetailsFields.style.display   = "block";
            if (addressFields)         addressFields.style.display         = "none";

            hasDeliveryZone = false;
            deliveryFee     = 0;

            if (deliveryFeeMessage) {
                deliveryFeeMessage.textContent = "Pickup selected – no delivery fee.";
            }
        } else {
            if (deliveryDetailsFields) deliveryDetailsFields.style.display = "none";
            if (pickupDetailsFields)   pickupDetailsFields.style.display   = "none";
            if (addressFields)         addressFields.style.display         = "none";

            hasDeliveryZone = false;
            deliveryFee     = 0;

            if (deliveryFeeMessage) {
                deliveryFeeMessage.textContent = "Select Delivery above to see delivery options.";
            }
        }

        syncCartState();
    }

    function handleSubdivisionChange() {
        const mode = orderDetails.fulfillmentMode;
        if (mode === "pickup") {
            hasDeliveryZone = false;
            deliveryFee     = 0;
            orderDetails.subdivision = "";
            saveOrderDetails();
            if (addressFields) addressFields.style.display = "none";
            if (deliveryFeeMessage) {
                deliveryFeeMessage.textContent = "Pickup selected – no delivery fee.";
            }
            syncCartState();
            return;
        }

        const val = subdivisionSelect ? subdivisionSelect.value : "";
        if (!val) {
            hasDeliveryZone = false;
            deliveryFee     = 0;
            orderDetails.subdivision = "";
            saveOrderDetails();
            if (addressFields) addressFields.style.display = "none";
            if (deliveryFeeMessage) {
                deliveryFeeMessage.textContent = "Select your subdivision to calculate delivery fee.";
            }
            syncCartState();
            return;
        }

        const zone = DELIVERY_ZONES[val];
        if (!zone) {
            hasDeliveryZone = false;
            deliveryFee     = 0;
            orderDetails.subdivision = "";
            saveOrderDetails();
            if (addressFields) addressFields.style.display = "none";
            syncCartState();
            return;
        }

        hasDeliveryZone = true;
        deliveryFee     = zone.fee + zone.gate;
        orderDetails.subdivision = val;
        saveOrderDetails();

        if (addressFields) addressFields.style.display = "block";
        if (deliveryFeeMessage) {
            deliveryFeeMessage.innerHTML = `
                Delivery fee: <strong>₱${zone.fee}</strong>
                ${zone.gate ? ` + Gate fee: <strong>₱${zone.gate}</strong>` : ""}
                = <strong>₱${deliveryFee}</strong>
            `;
        }

        syncCartState();
    }

    // ---------------------- Bind inputs -> orderDetails ----------------------
    if (addrBlkLot) {
        addrBlkLot.addEventListener("input", () => {
            orderDetails.blockLot = addrBlkLot.value.trim();
            saveOrderDetails();
        });
    }

    if (addrLandmark) {
        addrLandmark.addEventListener("input", () => {
            orderDetails.landmark = addrLandmark.value.trim();
            saveOrderDetails();
        });
    }

    if (addrName) {
        addrName.addEventListener("input", () => {
            orderDetails.customerName = addrName.value.trim();
            // keep pickup in sync if empty
            if (pickupName && !pickupName.value) {
                pickupName.value = addrName.value;
            }
            saveOrderDetails();
        });
    }

    if (addrPhone) {
        addrPhone.addEventListener("input", () => {
            orderDetails.phone = addrPhone.value.trim();
            if (pickupPhone && !pickupPhone.value) {
                pickupPhone.value = addrPhone.value;
            }
            saveOrderDetails();
        });
    }

    if (pickupName) {
        pickupName.addEventListener("input", () => {
            orderDetails.customerName = pickupName.value.trim();
            if (addrName && !addrName.value) {
                addrName.value = pickupName.value;
            }
            saveOrderDetails();
        });
    }

    if (pickupPhone) {
        pickupPhone.addEventListener("input", () => {
            orderDetails.phone = pickupPhone.value.trim();
            if (addrPhone && !addrPhone.value) {
                addrPhone.value = pickupPhone.value;
            }
            saveOrderDetails();
        });
    }

    // ---------------------- Init from localStorage ----------------------
    loadOrderDetails();

    if (orderDetails.fulfillmentMode === "delivery" && deliveryRadio) {
        deliveryRadio.checked = true;
    } else if (orderDetails.fulfillmentMode === "pickup" && pickupRadio) {
        pickupRadio.checked = true;
    }

    if (subdivisionSelect && orderDetails.subdivision) {
        subdivisionSelect.value = orderDetails.subdivision;
    }

    if (addrBlkLot)   addrBlkLot.value   = orderDetails.blockLot   || "";
    if (addrLandmark) addrLandmark.value = orderDetails.landmark   || "";
    if (addrName)     addrName.value     = orderDetails.customerName || "";
    if (addrPhone)    addrPhone.value    = orderDetails.phone      || "";

    if (pickupName && !pickupName.value)   pickupName.value   = orderDetails.customerName || "";
    if (pickupPhone && !pickupPhone.value) pickupPhone.value  = orderDetails.phone || "";

    updateFulfillmentUI();

    if (subdivisionSelect && orderDetails.subdivision) {
        handleSubdivisionChange();
    }

    // ---------------------- Radio change bindings ----------------------
    if (deliveryRadio) {
        deliveryRadio.addEventListener("change", () => {
            if (!deliveryRadio.checked) return;
            orderDetails.fulfillmentMode = "delivery";
            saveOrderDetails();
            updateFulfillmentUI();
            handleSubdivisionChange();
        });
    }

    if (pickupRadio) {
        pickupRadio.addEventListener("change", () => {
            if (!pickupRadio.checked) return;
            orderDetails.fulfillmentMode = "pickup";
            saveOrderDetails();
            updateFulfillmentUI();
        });
    }

    if (subdivisionSelect) {
        subdivisionSelect.addEventListener("change", handleSubdivisionChange);
    }
});
