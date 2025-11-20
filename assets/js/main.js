document.addEventListener("DOMContentLoaded", function () {
    // =========================
    // HERO BACKGROUND SLIDER
    // =========================
    const slides = document.querySelectorAll(".hero-bg-slide");
    if (slides.length) {
        let current = Math.floor(Math.random() * slides.length);
        slides[current].classList.add("active");

        const intervalMs = 5000;

        setInterval(() => {
            slides[current].classList.remove("active");
            current = (current + 1) % slides.length;
            slides[current].classList.add("active");
        }, intervalMs);
    }

    // =========================
    // BACK TO TOP BUTTON
    // =========================
    const backToTopBtn = document.querySelector(".back-to-top");

    if (backToTopBtn) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add("show");
            } else {
                backToTopBtn.classList.remove("show");
            }
        });

        backToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }

    // =========================
    // MOBILE ORDER BAR
    // =========================
    const mobileOrderBar = document.querySelector(".mobile-order-bar");
    if (mobileOrderBar) {
        let lastScrollY = window.scrollY;

        window.addEventListener("scroll", () => {
            const currentY = window.scrollY;

            if (currentY > lastScrollY && currentY > 100) {
                // scrolling down
                mobileOrderBar.classList.add("mobile-order-bar-hidden");
            } else {
                // scrolling up
                mobileOrderBar.classList.remove("mobile-order-bar-hidden");
            }

            lastScrollY = currentY;
        });
    }

    // =========================
    // REVEAL ON SCROLL
    // =========================
    const revealEls = document.querySelectorAll(".reveal-on-scroll");
    if (revealEls.length && "IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("reveal-visible");
                        obs.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.15
            }
        );

        revealEls.forEach((el) => observer.observe(el));
    }

// =========================
// MENU VARIANT SELECTOR + CART
// =========================

// ---- global extras list ----
const MENU_EXTRAS = [
    { id: "extra-meat",          label: "Meat",          price: 30 },
    { id: "extra-rice",          label: "Rice",          price: 20 },
    { id: "extra-veggies",       label: "Veggies",       price: 20 },
    { id: "extra-garlic-sauce",  label: "Garlic Sauce",  price: 15 },
    { id: "extra-cheese-sauce",  label: "Cheese Sauce",  price: 15 },
    { id: "extra-sliced-cheese", label: "Sliced Cheese", price: 15 },
    { id: "extra-orange-cheese", label: "Orange Cheese", price: 30 },
    { id: "extra-salsa",         label: "Salsa",         price: 20 }
];

// ---- product configs (same prices as before) ----
const MENU_VARIANTS = {
    "shawarma-wrap": {
        name: "Shawarma Wrap",
        description: "Classic shawarma wrap. Choose meat, size and spice level.",
        groups: [
            {
                key: "meat",
                label: "Meat",
                options: [
                    { id: "meat-beef",    label: "Beef" },
                    { id: "meat-chicken", label: "Chicken" }
                ]
            },
            {
                key: "size",
                label: "Size / Deal",
                options: [
                    { id: "regular-solo", label: "REGULAR Solo",         price: 70 },
                    { id: "regular-b1t1", label: "REGULAR Buy 1 Take 1", price: 135 },
                    { id: "large-solo",   label: "LARGE Solo",           price: 85 },
                    { id: "large-b1t1",   label: "LARGE Buy 1 Take 1",   price: 165 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 70
    },

    // SPECIAL WRAPS
    "special-overload-wrap": {
        name: "Overload Wrap",
        description: "Loaded with more meat, veggies and cheese.",
        groups: [
            {
                key: "meat",
                label: "Meat",
                options: [
                    { id: "meat-beef",    label: "Beef" },
                    { id: "meat-chicken", label: "Chicken" }
                ]
            },
            {
                key: "size",
                label: "Size",
                options: [
                    { id: "regular", label: "Regular", price: 95 },
                    { id: "large",   label: "Large",   price: 110 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 95
    },

    "special-double-cheese-wrap": {
        name: "Double Cheese Wrap",
        description: "Filled with sliced cheese and orange cheese.",
        groups: [
            {
                key: "meat",
                label: "Meat",
                options: [
                    { id: "meat-beef",    label: "Beef" },
                    { id: "meat-chicken", label: "Chicken" }
                ]
            },
            {
                key: "size",
                label: "Size",
                options: [
                    { id: "regular", label: "Regular", price: 90 },
                    { id: "large",   label: "Large",   price: 105 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 90
    },

    "special-all-meat-wrap": {
        name: "All Meat Wrap",
        description: "Pure beef meat, no veggies.",
        groups: [
            {
                key: "meat",
                label: "Meat",
                options: [
                    { id: "meat-beef",    label: "Beef" },
                    { id: "meat-chicken", label: "Chicken" }
                ]
            },
            {
                key: "size",
                label: "Size",
                options: [
                    { id: "regular", label: "Regular", price: 80 },
                    { id: "large",   label: "Large",   price: 95 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 80
    },

    // SHAWARMA RICE
    "shawarma-rice-regular": {
        name: "Shawarma Rice",
        description: "Rice bowl topped with shawarma meat, veggies and sauces.",
        groups: [
            {
                key: "meat",
                label: "Meat",
                options: [
                    { id: "meat-beef",    label: "Beef" },
                    { id: "meat-chicken", label: "Chicken" }
                ]
            },
            {
                key: "serving",
                label: "Serving",
                options: [
                    { id: "solo",     label: "Solo",     price: 95 },
                    { id: "overload", label: "Overload", price: 150 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 95
    },

    "shawarma-rice-all-meat": {
        name: "All Meat Shawarma Rice",
        description: "Rice bowl topped with pure shawarma meat, no veggies.",
        groups: [
            {
                key: "meat",
                label: "Meat",
                options: [
                    { id: "meat-beef",    label: "Beef" },
                    { id: "meat-chicken", label: "Chicken" }
                ]
            },
            {
                key: "serving",
                label: "Serving",
                options: [
                    { id: "solo",     label: "Solo",     price: 105 },
                    { id: "overload", label: "Overload", price: 165 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 105
    },

    // PREMIUM STEAK & FRIES (2 products: Premium + Double Cheese)
    "premium-steak": {
        name: "Premium Steak & Fries",
        description: "Tray of fries topped with premium beef and sauces.",
        groups: [
            {
                key: "size",
                label: "Serving",
                options: [
                    { id: "regular", label: "Regular", price: 100 },
                    { id: "large",   label: "Large",   price: 125 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 100
    },

    "premium-steak-double-cheese": {
        name: "Double Cheese Premium Steak & Fries",
        description: "Premium steak tray loaded with extra cheese.",
        groups: [
            {
                key: "size",
                label: "Serving",
                options: [
                    { id: "regular", label: "Regular", price: 130 },
                    { id: "large",   label: "Large",   price: 155 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 130
    },

    // COATED FRIES & NACHOS
    "coated-fries": {
        name: "Coated Fries",
        description: "Crispy fries in a flavored coating.",
        groups: [
            {
                key: "serving",
                label: "Serving",
                options: [
                    { id: "solo",   label: "Solo",   price: 98 },
                    { id: "bucket", label: "Bucket", price: 190 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        excludeExtras: ["extra-rice"],
        startingFrom: 98
    },

    "beefy-coated-fries": {
        name: "Beefy Coated Fries",
        description: "Coated fries topped with beef shawarma.",
        groups: [
            {
                key: "serving",
                label: "Serving",
                options: [
                    { id: "solo",   label: "Solo",   price: 120 },
                    { id: "bucket", label: "Bucket", price: 215 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        excludeExtras: ["extra-rice"],
        startingFrom: 120
    },

    "cheesy-beef-coated-fries": {
        name: "Cheesy Beef Coated Fries",
        description: "Beefy coated fries with extra cheese.",
        groups: [
            {
                key: "serving",
                label: "Serving",
                options: [
                    { id: "solo",   label: "Solo",   price: 150 },
                    { id: "bucket", label: "Bucket", price: 290 }
                ]
            }
        ],
        spiceToggle: true,
        extrasEnabled: true,
        excludeExtras: ["extra-rice"],
        startingFrom: 150
    },

    "nacho-shawarma": {
        name: "Nacho Shawarma",
        description: "Nachos piled with beef, veggies and sauces.",
        options: [
            { id: "regular", label: "Regular", price: 170 }
        ],
        autoSelect: true,
        spiceToggle: true,
        extrasEnabled: true,
        excludeExtras: ["extra-rice"],
        startingFrom: 170
    },

    "nacho-bandido": {
        name: "Nacho Bandido",
        description: "Another loaded nacho favorite from the depot.",
        options: [
            { id: "regular", label: "Regular", price: 150 }
        ],
        autoSelect: true,
        spiceToggle: true,
        extrasEnabled: true,
        excludeExtras: ["extra-rice"],
        startingFrom: 150
    },

    // ALA CARTE
    "ala-meat-veggies": {
        name: "Meat & Veggies Ala Carte",
        description: "Shawarma meat with veggies and sauces on a plate.",
        options: [
            { id: "plate", label: "Plate", price: 210 }
        ],
        autoSelect: true,
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 210
    },

    "ala-all-meat": {
        name: "All Meat Ala Carte",
        description: "Pure shawarma meat served on a plate.",
        options: [
            { id: "plate", label: "Plate", price: 240 }
        ],
        autoSelect: true,
        spiceToggle: true,
        extrasEnabled: true,
        startingFrom: 240
    },

    // BEVERAGES
    "drinks": {
        name: "Beverages",
        description: "Refreshments to go with your shawarma.",
        options: [
            { id: "plum-regular", label: "Plum Tea Regular", price: 60 },
            { id: "plum-large",   label: "Plum Tea Large",   price: 70 },
            { id: "coke-mismo",   label: "Coke Mismo",       price: 25 },
            { id: "royal-mismo",  label: "Royal Mismo",      price: 25 }
        ],
        spiceToggle: false,
        extrasEnabled: false,
        startingFrom: 25
    }
};

// ---- CART STATE ----
const DELIVERY_FEE = 20;
let deliveryAllowed = true;              // depends on location selector
let fulfillmentMode = "delivery";        // "delivery" | "pickup"
const cart = [];                         // array of { key, name, summary, unitPrice, qty }

const cartItemsEl       = document.getElementById("cartItems");
const cartSubtotalEl    = document.getElementById("cartSubtotal");
const cartDeliveryFeeEl = document.getElementById("cartDeliveryFee");
const cartTotalEl       = document.getElementById("cartTotal");
const deliveryRadio     = document.getElementById("cartDelivery");
const pickupRadio       = document.getElementById("cartPickup");
const deliveryNoteEl    = document.getElementById("cartDeliveryNote");

// helper
const formatPeso = (n) => "₱" + (n || 0);

// update_cart_totals + render
function updateCartTotals() {
    const subtotalEl = document.getElementById("cartSubtotal");
    const deliveryFeeEl = document.getElementById("cartDeliveryFee");
    const totalEl = document.getElementById("cartTotal");

    let subtotal = cart.reduce((sum, item) => sum + (item.unitPrice * item.qty), 0);

    let fee = deliveryFee;

    // If pickup selected => no delivery fee
    if (pickupRadio && pickupRadio.checked) {
        fee = 0;
    }

    subtotalEl.textContent = formatPeso(subtotal);
    deliveryFeeEl.textContent = formatPeso(fee);
    totalEl.textContent = formatPeso(subtotal + fee);
}


function renderCart() {
    if (!cartItemsEl) return;

    // update total item count badge
    const itemCountEl = document.getElementById("cartItemCount");
    const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    if (itemCountEl) {
        itemCountEl.textContent = totalQty;
    }

    if (!cart.length) {
        cartItemsEl.classList.add("text-muted");
        cartItemsEl.innerHTML = "No items yet. Add something from the menu.";
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

    // attach inc/dec handlers
    cartItemsEl.querySelectorAll(".js-cart-inc").forEach(btn => {
        btn.addEventListener("click", () => {
            const idx = parseInt(btn.closest("[data-cart-index]").dataset.cartIndex, 10);
            cart[idx].qty += 1;
            renderCart();
        });
    });

    cartItemsEl.querySelectorAll(".js-cart-dec").forEach(btn => {
        btn.addEventListener("click", () => {
            const idx = parseInt(btn.closest("[data-cart-index]").dataset.cartIndex, 10);
            cart[idx].qty -= 1;
            if (cart[idx].qty <= 0) {
                cart.splice(idx, 1);
            }
            renderCart();
        });
    });

    updateCartTotals();
}


// add/merge line in cart
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

    let existing = cart.find(item => item.key === key);
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
}

// ---- LOCATION SELECTOR / FULFILLMENT ----
const locationSelect   = document.getElementById("locationSelect");
const locationMessage  = document.getElementById("locationMessage");
const locationDetails  = document.getElementById("locationDetails");
const locationMap      = document.getElementById("locationMap");

if (locationSelect && locationMessage) {
    locationSelect.addEventListener("change", () => {
        if (locationSelect.value === "san-marino") {
            deliveryAllowed = true;
            fulfillmentMode = "delivery";

            if (deliveryRadio) {
                deliveryRadio.disabled = false;
                deliveryRadio.checked = true;
            }
            if (pickupRadio) {
                pickupRadio.checked = false;
            }

            locationMessage.textContent =
                "We can deliver to your area. Please confirm your exact address below.";
            if (deliveryNoteEl) {
                deliveryNoteEl.textContent = "Delivery fee is ₱20 within San Marino City.";
            }

            if (locationDetails) {
                locationDetails.style.display = "block";
            }

            // OPTIONAL: if you want to change the map based on something later,
            // you can tweak locationMap.src here.

        } else {
            // outside service area
            deliveryAllowed = false;
            fulfillmentMode = "pickup";

            if (deliveryRadio) {
                deliveryRadio.checked = false;
                deliveryRadio.disabled = true;
            }
            if (pickupRadio) {
                pickupRadio.checked = true;
            }

            locationMessage.textContent =
                "Outside San Marino City – only pickup (or Foodpanda / Grab) is available.";
            if (deliveryNoteEl) {
                deliveryNoteEl.textContent =
                    "Outside area: please use pickup or Foodpanda / Grab.";
            }

            if (locationDetails) {
                locationDetails.style.display = "none";
            }
        }

        updateCartTotals();
    });

    // run once on page load to make sure everything matches default
    locationSelect.dispatchEvent(new Event("change"));
}

// fulfillment radio
if (deliveryRadio && pickupRadio) {
    deliveryRadio.addEventListener("change", () => {
        if (deliveryRadio.checked) {
            fulfillmentMode = "delivery";
            updateCartTotals();
        }
    });
    pickupRadio.addEventListener("change", () => {
        if (pickupRadio.checked) {
            fulfillmentMode = "pickup";
            updateCartTotals();
        }
    });
}

// ---- MODAL WIRING ----
const variantButtons = document.querySelectorAll(".js-open-variants");
const variantModalEl = document.getElementById("variantModal");

let CURRENT_CONFIG = null; // updated every time user changes selections

if (variantButtons.length && variantModalEl && typeof bootstrap !== "undefined") {
    const modalTitle = variantModalEl.querySelector("#variantModalLabel");
    const modalDesc = variantModalEl.querySelector("#variantModalDescription");
    const optionsContainer = variantModalEl.querySelector("#variantModalOptions");
    const priceHint = variantModalEl.querySelector("#variantModalPriceHint");
    const addBtn = variantModalEl.querySelector("#variantModalAddBtn");

    const variantModal = new bootstrap.Modal(variantModalEl);

    const buildExtrasSection = (product, onChange) => {
        if (!product.extrasEnabled) return;

        const allowedExtras = MENU_EXTRAS.filter(opt =>
            !(product.excludeExtras && product.excludeExtras.includes(opt.id))
        );
        if (!allowedExtras.length) return;

        const section = document.createElement("div");
        section.className = "mb-3";

        const heading = document.createElement("h6");
        heading.className = "mb-2 small text-uppercase text-muted";
        heading.textContent = "Extras";
        section.appendChild(heading);

        const list = document.createElement("div");
        list.className = "list-group small";

        allowedExtras.forEach((opt) => {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className =
                "list-group-item list-group-item-action d-flex justify-content-between align-items-center";
            btn.innerHTML = `
                <span>${opt.label}</span>
                <span class="fw-semibold price-tag mb-0">₱${opt.price}</span>
            `;
            btn.addEventListener("click", () => {
                const active = btn.classList.toggle("active");
                onChange(opt, active);
            });
            list.appendChild(btn);
        });

        section.appendChild(list);
        optionsContainer.appendChild(section);
    };

    const buildSpiceSection = (product, onChange) => {
        if (!product.spiceToggle) return;

        const section = document.createElement("div");
        section.className = "mb-2";

        const heading = document.createElement("h6");
        heading.className = "mb-2 small text-uppercase text-muted";
        heading.textContent = "Spice Level";
        section.appendChild(heading);

        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "list-group-item list-group-item-action text-start";
        btn.textContent = "Spicy";

        let spicyFlag = false;

        btn.addEventListener("click", () => {
            spicyFlag = !spicyFlag;
            btn.classList.toggle("active", spicyFlag);
            onChange(spicyFlag);
        });

        section.appendChild(btn);
        optionsContainer.appendChild(section);
    };

    variantButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const key = btn.getAttribute("data-product-id");
            const product = MENU_VARIANTS[key];
            if (!product) return;

            CURRENT_CONFIG = null;

            modalTitle.textContent = product.name;
            modalDesc.textContent = product.description || "";
            optionsContainer.innerHTML = "";
            priceHint.textContent = "";

            // GROUPED PRODUCTS
            if (product.groups && product.groups.length) {
                const selections = {};
                const extrasSelected = new Map();
                let spicyFlag = false;

                const getPricedSelection = () => {
                    for (const k in selections) {
                        if (selections[k] && typeof selections[k].price === "number") {
                            return selections[k];
                        }
                    }
                    return null;
                };

                const updateHint = () => {
                    const priced = getPricedSelection();
                    if (!priced) {
                        priceHint.textContent = "Pick your options to see the price.";
                        CURRENT_CONFIG = null;
                        return;
                    }

                    const labels = [];
                    Object.keys(selections).forEach((k) => {
                        if (selections[k]) labels.push(selections[k].label);
                    });

                    if (product.spiceToggle && spicyFlag) {
                        labels.push("Spicy");
                    }

                    let extrasTotal = 0;
                    const extrasArr = [];
                    extrasSelected.forEach((opt) => {
                        extrasTotal += opt.price || 0;
                        extrasArr.push(opt);
                    });

                    if (extrasArr.length) {
                        labels.push(`${extrasArr.length} extra${extrasArr.length > 1 ? "s" : ""}`);
                    }

                    const total = priced.price + extrasTotal;
                    const labelText = labels.join(" • ");

                    priceHint.textContent = `${labelText} – ₱${total}`;

                    CURRENT_CONFIG = {
                        productKey: key,
                        productName: product.name,
                        baseLabel: priced.label,
                        basePrice: priced.price,
                        meatLabel: selections.meat ? selections.meat.label : null,
                        sizeLabel: selections.size ? selections.size.label : (selections.serving ? selections.serving.label : null),
                        spicy: spicyFlag,
                        extrasTotal,
                        extrasIds: extrasArr.map(e => e.id),
                        summaryText: labelText
                    };
                };

                const makeGroupSection = (group) => {
                    const section = document.createElement("div");
                    section.className = "mb-3";

                    const heading = document.createElement("h6");
                    heading.className = "mb-2 small text-uppercase text-muted";
                    heading.textContent = group.label;
                    section.appendChild(heading);

                    const list = document.createElement("div");
                    list.className = "list-group small";

                    group.options.forEach((opt) => {
                        const optionBtn = document.createElement("button");
                        optionBtn.type = "button";
                        optionBtn.className =
                            "list-group-item list-group-item-action d-flex justify-content-between align-items-center";

                        if (typeof opt.price === "number") {
                            optionBtn.innerHTML = `
                                <span>${opt.label}</span>
                                <span class="fw-semibold price-tag mb-0">₱${opt.price}</span>
                            `;
                        } else {
                            optionBtn.innerHTML = `<span>${opt.label}</span>`;
                        }

                        optionBtn.addEventListener("click", () => {
                            list.querySelectorAll(".active").forEach(el =>
                                el.classList.remove("active")
                            );
                            optionBtn.classList.add("active");

                            selections[group.key] = opt;
                            updateHint();
                        });

                        list.appendChild(optionBtn);
                    });

                    section.appendChild(list);
                    optionsContainer.appendChild(section);
                };

                product.groups.forEach(makeGroupSection);

                buildSpiceSection(product, (flag) => {
                    spicyFlag = flag;
                    updateHint();
                });

                buildExtrasSection(product, (opt, active) => {
                    if (active) extrasSelected.set(opt.id, opt);
                    else extrasSelected.delete(opt.id);
                    updateHint();
                });

                updateHint();

            // SIMPLE PRODUCTS
            } else if (product.options) {
                let selected = null;
                const extrasSelected = new Map();
                let spicyFlag = false;

                const updateHint = () => {
                    if (!selected) {
                        priceHint.textContent = "Pick an option to see the price.";
                        CURRENT_CONFIG = null;
                        return;
                    }

                    let extrasTotal = 0;
                    const extrasArr = [];
                    extrasSelected.forEach((opt) => {
                        extrasTotal += opt.price || 0;
                        extrasArr.push(opt);
                    });

                    const labels = [selected.label];
                    if (product.spiceToggle && spicyFlag) labels.push("Spicy");
                    if (extrasArr.length) {
                        labels.push(`${extrasArr.length} extra${extrasArr.length > 1 ? "s" : ""}`);
                    }

                    const total = selected.price + extrasTotal;
                    const labelText = labels.join(" • ");

                    priceHint.textContent = `${labelText} – ₱${total}`;

                    CURRENT_CONFIG = {
                        productKey: key,
                        productName: product.name,
                        baseLabel: selected.label,
                        basePrice: selected.price,
                        meatLabel: null,
                        sizeLabel: null,
                        spicy: spicyFlag,
                        extrasTotal,
                        extrasIds: extrasArr.map(e => e.id),
                        summaryText: labelText
                    };
                };

                // auto-select single option (e.g., nachos plates)
                if (product.autoSelect && product.options.length === 1) {
                    selected = product.options[0];

                    const section = document.createElement("div");
                    section.className = "mb-3";
                    section.innerHTML = `
                        <h6 class="mb-2 small text-uppercase text-muted">Base</h6>
                        <div class="list-group small">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${selected.label}</span>
                                <span class="fw-semibold price-tag mb-0">₱${selected.price}</span>
                            </div>
                        </div>
                    `;
                    optionsContainer.appendChild(section);
                } else {
                    product.options.forEach((opt) => {
                        const optionBtn = document.createElement("button");
                        optionBtn.type = "button";
                        optionBtn.className =
                            "list-group-item list-group-item-action d-flex justify-content-between align-items-center";
                        optionBtn.innerHTML = `
                            <span>${opt.label}</span>
                            <span class="fw-semibold price-tag mb-0">₱${opt.price}</span>
                        `;
                        optionBtn.addEventListener("click", () => {
                            optionsContainer
                                .querySelectorAll(".list-group-item.active")
                                .forEach(el => el.classList.remove("active"));
                            optionBtn.classList.add("active");
                            selected = opt;
                            updateHint();
                        });
                        optionsContainer.appendChild(optionBtn);
                    });
                }

                buildSpiceSection(product, (flag) => {
                    spicyFlag = flag;
                    updateHint();
                });

                buildExtrasSection(product, (opt, active) => {
                    if (active) extrasSelected.set(opt.id, opt);
                    else extrasSelected.delete(opt.id);
                    updateHint();
                });

                updateHint();
            }

            variantModal.show();
        });
    });

    if (addBtn) {
        addBtn.addEventListener("click", () => {
            if (!CURRENT_CONFIG || !CURRENT_CONFIG.basePrice) {
                if (priceHint) {
                    priceHint.textContent = "Please pick your options first.";
                }
                return;
            }
            addToCartFromConfig(CURRENT_CONFIG);
            variantModal.hide();
        });
    }
}

let deliveryFee = 0;

// subdivision → delivery fee + gate fee
const DELIVERY_ZONES = {
    // San Marino (₱15, except Classic has gate)
    "classic":  { fee: 15, gate: 15 },
    "heights":  { fee: 15, gate: 0 },
    "central":  { fee: 15, gate: 0 },
    "phase1":   { fee: 15, gate: 0 },
    "phase2":   { fee: 15, gate: 0 },
    "phase3":   { fee: 15, gate: 0 },
    "phase4":   { fee: 15, gate: 0 },
    "phase5":   { fee: 15, gate: 0 },
    "north1":   { fee: 15, gate: 0 },
    "north2":   { fee: 15, gate: 0 },
    "south1":   { fee: 15, gate: 0 },
    "south2":   { fee: 15, gate: 0 },

    // Outside (NDGV)
    "ndgv1": { fee: 25, gate: 10 },
    "ndgv2": { fee: 25, gate: 10 },

    // Totally outside
    "outside": { fee: 0, gate: 0 }
};

const subdivisionSelect = document.getElementById("subdivisionSelect");
const deliveryMessage   = document.getElementById("deliveryMessage");
const addressFields     = document.getElementById("addressFields");

if (subdivisionSelect) {
    subdivisionSelect.addEventListener("change", () => {
        const val = subdivisionSelect.value;

        if (!val) {
            addressFields.style.display = "none";
            deliveryFee = 0;
            deliveryMessage.textContent = "Select your subdivision to calculate delivery fee.";
            updateCartTotals();
            return;
        }

        if (val === "outside") {
            // outside service area => no delivery
            addressFields.style.display = "none";
            deliveryFee = 0;
            deliveryMessage.innerHTML =
                "<span class='text-danger fw-bold'>Outside service area.</span> Only pickup is available.";
            
            if (deliveryRadio) {
                deliveryRadio.checked = false;
                deliveryRadio.disabled = true;
            }
            if (pickupRadio) pickupRadio.checked = true;

            updateCartTotals();
            return;
        }

        // inside supported zones
        const zone = DELIVERY_ZONES[val];
        addressFields.style.display = "block";

        deliveryFee = zone.fee + zone.gate;

        deliveryMessage.innerHTML = `
            Delivery Fee: <strong>₱${zone.fee}</strong>
            ${zone.gate ? ` + Gate Fee: <strong>₱${zone.gate}</strong>` : ""}
            = <strong>₱${deliveryFee}</strong>
        `;

        // enable delivery
        if (deliveryRadio) {
            deliveryRadio.disabled = false;
            deliveryRadio.checked = true;
        }

        updateCartTotals();
    });
}


});