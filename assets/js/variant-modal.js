// variant-modal.js
document.addEventListener("DOMContentLoaded", function () {
    // ---------------------- Menu variants & extras config ----------------------
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

    const variantButtons = document.querySelectorAll(".js-open-variants");
    const variantModalEl = document.getElementById("variantModal");

    if (!variantButtons.length || !variantModalEl || typeof bootstrap === "undefined" || typeof window.addToCartFromConfig !== "function") {
        return;
    }

    const modalTitle        = variantModalEl.querySelector("#variantModalLabel");
    const modalDesc         = variantModalEl.querySelector("#variantModalDescription");
    const optionsContainer  = variantModalEl.querySelector("#variantModalOptions");
    const priceHint         = variantModalEl.querySelector("#variantModalPriceHint");
    const addBtn            = variantModalEl.querySelector("#variantModalAddBtn");

    const variantModal = new bootstrap.Modal(variantModalEl);
    let CURRENT_CONFIG = null;

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
            const key     = btn.getAttribute("data-product-id");
            const product = MENU_VARIANTS[key];
            if (!product) return;

            CURRENT_CONFIG = null;

            modalTitle.textContent   = product.name;
            modalDesc.textContent    = product.description || "";
            optionsContainer.innerHTML = "";
            priceHint.textContent      = "";

            // Grouped products
            if (product.groups && product.groups.length) {
                const selections    = {};
                const extrasSelected = new Map();
                let spicyFlag       = false;

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
                        const extrasNames = extrasArr.map((e) => e.label).join(", ");
                        const countLabel  = `${extrasArr.length} extra${extrasArr.length > 1 ? "s" : ""}`;
                        labels.push(`${countLabel} (${extrasNames})`);
                    }


                    const total     = priced.price + extrasTotal;
                    const labelText = labels.join(" • ");

                    priceHint.textContent = `${labelText} – ₱${total}`;

                    CURRENT_CONFIG = {
                        productKey:  key,
                        productName: product.name,
                        baseLabel:   priced.label,
                        basePrice:   priced.price,
                        meatLabel:   selections.meat ? selections.meat.label : null,
                        sizeLabel:   selections.size
                            ? selections.size.label
                            : (selections.serving ? selections.serving.label : null),
                        spicy:       spicyFlag,
                        extrasTotal,
                        extrasIds:   extrasArr.map((e) => e.id),
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
                            list.querySelectorAll(".active").forEach((el) =>
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
                    if (active) {
                        extrasSelected.set(opt.id, opt);
                    } else {
                        extrasSelected.delete(opt.id);
                    }
                    updateHint();
                });

                updateHint();

            // Simple products
            } else if (product.options) {
                let selected        = null;
                const extrasSelected = new Map();
                let spicyFlag       = false;

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

                    const total     = selected.price + extrasTotal;
                    const labelText = labels.join(" • ");

                    priceHint.textContent = `${labelText} – ₱${total}`;

                    CURRENT_CONFIG = {
                        productKey:  key,
                        productName: product.name,
                        baseLabel:   selected.label,
                        basePrice:   selected.price,
                        meatLabel:   null,
                        sizeLabel:   null,
                        spicy:       spicyFlag,
                        extrasTotal,
                        extrasIds:   extrasArr.map((e) => e.id),
                        summaryText: labelText
                    };
                };

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
            window.addToCartFromConfig(CURRENT_CONFIG);
            variantModal.hide();
        });
    }
});
