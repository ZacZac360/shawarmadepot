document.addEventListener("DOMContentLoaded", function () {
    // ---------------------- Hero background slider ----------------------

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

    // ---------------------- Reels background slider ----------------------

    const reelsSlides = document.querySelectorAll(".reels-bg-slide");
    if (reelsSlides.length) {
        let reelsCurrent = Math.floor(Math.random() * reelsSlides.length);
        reelsSlides[reelsCurrent].classList.add("active");

        const reelsIntervalMs = 5000;

        setInterval(() => {
            reelsSlides[reelsCurrent].classList.remove("active");
            reelsCurrent = (reelsCurrent + 1) % reelsSlides.length;
            reelsSlides[reelsCurrent].classList.add("active");
        }, reelsIntervalMs);
    }

    // ---------------------- Back-to-top button ----------------------

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

    // ---------------------- Mobile order bar ----------------------

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

    // ---------------------- Reveal on scroll ----------------------

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

    // ---------------------- Menu variants & cart: config ----------------------

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

    const MENU_VARIANTS = {
        // Shawarma wrap
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

        // Special wraps
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

        // Shawarma rice
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

        // Premium steak & fries
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

        // Coated fries & nachos
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

        // Ala carte
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

        // Beverages
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

    // ---------------------- Cart state & DOM refs ----------------------

    let deliveryFee     = 0;
    let hasDeliveryZone = false;
    let fulfillmentMode = "";

    const cart = []; // { key, name, summary, unitPrice, qty }

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

    const deliveryRadio         = document.getElementById("fulfillmentDelivery");
    const pickupRadio           = document.getElementById("fulfillmentPickup");
    const deliveryDetailsFields = document.getElementById("deliveryDetailsFields");
    const subdivisionSelect     = document.getElementById("subdivisionSelect");
    const addressFields         = document.getElementById("addressFields");
    const deliveryFeeMessage    = document.getElementById("deliveryFeeMessage");

    const formatPeso = (n) => "₱" + (n || 0);

    // ---------------------- Fulfillment toggle ----------------------

    function updateFulfillment() {
        if (deliveryRadio && deliveryRadio.checked) {
            fulfillmentMode = "delivery";

            if (deliveryDetailsFields) deliveryDetailsFields.style.display = "block";

            if (!hasDeliveryZone) {
                deliveryFee = 0;
                if (deliveryFeeMessage) {
                    deliveryFeeMessage.textContent = "Select your subdivision to calculate delivery fee.";
                }
            }

            if (cartFulfillmentNoteEl) {
                cartFulfillmentNoteEl.textContent =
                    "Delivery selected – delivery fee depends on your subdivision.";
            }

        } else if (pickupRadio && pickupRadio.checked) {
            fulfillmentMode = "pickup";

            if (deliveryDetailsFields) deliveryDetailsFields.style.display = "none";
            if (subdivisionSelect) subdivisionSelect.value = "";
            if (addressFields) addressFields.style.display = "none";

            hasDeliveryZone = false;
            deliveryFee = 0;

            if (deliveryFeeMessage) {
                deliveryFeeMessage.textContent = "Pickup selected – no delivery fee.";
            }
            if (cartFulfillmentNoteEl) {
                cartFulfillmentNoteEl.textContent = "Pickup selected – delivery fee is ₱0.";
            }

        } else {
            fulfillmentMode = "";

            if (deliveryDetailsFields) deliveryDetailsFields.style.display = "none";
            if (subdivisionSelect) subdivisionSelect.value = "";
            if (addressFields) addressFields.style.display = "none";

            hasDeliveryZone = false;
            deliveryFee = 0;

            if (deliveryFeeMessage) {
                deliveryFeeMessage.textContent = "Select Delivery above to see delivery options.";
            }
            if (cartFulfillmentNoteEl) {
                cartFulfillmentNoteEl.textContent = "Choose Delivery or Pickup above.";
            }
        }

        updateCartTotals();
    }

    // ---------------------- Cart totals ----------------------

    function updateCartTotals() {
        if (!cartSubtotalEl || !cartDeliveryFeeEl || !cartTotalEl) return;

        const subtotal = cart.reduce((sum, item) => sum + item.unitPrice * item.qty, 0);

        const showDeliveryFee =
            (fulfillmentMode === "delivery" && hasDeliveryZone);

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

        cartItemsEl.querySelectorAll(".js-cart-inc").forEach((btn) => {
            btn.addEventListener("click", () => {
                const idx = parseInt(btn.closest("[data-cart-index]").dataset.cartIndex, 10);
                cart[idx].qty += 1;
                renderCart();
            });
        });

        cartItemsEl.querySelectorAll(".js-cart-dec").forEach((btn) => {
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
    }

    // ---------------------- Variant modal wiring ----------------------

    const variantButtons = document.querySelectorAll(".js-open-variants");
    const variantModalEl = document.getElementById("variantModal");

    let CURRENT_CONFIG = null;

    if (variantButtons.length && variantModalEl && typeof bootstrap !== "undefined") {
        const modalTitle      = variantModalEl.querySelector("#variantModalLabel");
        const modalDesc       = variantModalEl.querySelector("#variantModalDescription");
        const optionsContainer = variantModalEl.querySelector("#variantModalOptions");
        const priceHint       = variantModalEl.querySelector("#variantModalPriceHint");
        const addBtn          = variantModalEl.querySelector("#variantModalAddBtn");

        const variantModal = new bootstrap.Modal(variantModalEl);

        const buildExtrasSection = (product, onChange) => {
            if (!product.extrasEnabled) return;

            const allowedExtras = MENU_EXTRAS.filter(
                (opt) => !(product.excludeExtras && product.excludeExtras.includes(opt.id))
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

                // Grouped products (meat + size, etc.)
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
                            labels.push(
                                `${extrasArr.length} extra${extrasArr.length > 1 ? "s" : ""}`
                            );
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
                            sizeLabel: selections.size
                                ? selections.size.label
                                : (selections.serving ? selections.serving.label : null),
                            spicy: spicyFlag,
                            extrasTotal,
                            extrasIds: extrasArr.map((e) => e.id),
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
                                list.querySelectorAll(".active").forEach((el) => {
                                    el.classList.remove("active");
                                });
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
                        if (active) {
                            extrasSelected.set(opt.id, opt);
                        } else {
                            extrasSelected.delete(opt.id);
                        }
                        updateHint();
                    });

                    updateHint();

                // Simple products (single options array)
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
                            labels.push(
                                `${extrasArr.length} extra${extrasArr.length > 1 ? "s" : ""}`
                            );
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
                            extrasIds: extrasArr.map((e) => e.id),
                            summaryText: labelText
                        };
                    };

                    // Auto-select single-option items
                    if (product.autoSelect && product.options.length === 1) {
                        selected = product.options[0];
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
                                    .forEach((el) => el.classList.remove("active"));
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
                        if (active) {
                            extrasSelected.set(opt.id, opt);
                        } else {
                            extrasSelected.delete(opt.id);
                        }
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

    // ---------------------- Delivery zones ----------------------

    const DELIVERY_ZONES = {
        // San Marino (₱15, except Classic has gate)
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

        // Outside (NDGV)
        ndgv: { fee: 25, gate: 10 },
        sdgv: { fee: 25, gate: 10 }
    };

    // ---------------------- Subdivision change handler ----------------------

    if (subdivisionSelect) {
        subdivisionSelect.addEventListener("change", () => {
            // Ignore subdivision if pickup
            if (fulfillmentMode === "pickup") {
                hasDeliveryZone = false;
                deliveryFee = 0;
                if (addressFields) addressFields.style.display = "none";
                if (deliveryFeeMessage) {
                    deliveryFeeMessage.textContent = "Pickup selected – no delivery fee.";
                }
                updateCartTotals();
                return;
            }

            const val = subdivisionSelect.value;

            if (!val) {
                hasDeliveryZone = false;
                deliveryFee = 0;
                if (addressFields) addressFields.style.display = "none";
                if (deliveryFeeMessage) {
                    deliveryFeeMessage.textContent = "Select your subdivision to calculate delivery fee.";
                }
                updateCartTotals();
                return;
            }

            const zone = DELIVERY_ZONES[val];
            if (!zone) {
                hasDeliveryZone = false;
                deliveryFee = 0;
                if (addressFields) addressFields.style.display = "none";
                updateCartTotals();
                return;
            }

            hasDeliveryZone = true;
            deliveryFee = zone.fee + zone.gate;

            if (addressFields) addressFields.style.display = "block";

            if (deliveryFeeMessage) {
                deliveryFeeMessage.innerHTML = `
                    Delivery fee: <strong>₱${zone.fee}</strong>
                    ${zone.gate ? ` + Gate fee: <strong>₱${zone.gate}</strong>` : ""}
                    = <strong>₱${deliveryFee}</strong>
                `;
            }

            updateCartTotals();
        });
    }

    // ---------------------- Event bindings & initial state ----------------------

    if (deliveryRadio) deliveryRadio.addEventListener("change", updateFulfillment);
    if (pickupRadio)   pickupRadio.addEventListener("change", updateFulfillment);

    updateFulfillment();
});
