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
    // MENU VARIANT SELECTOR
    // =========================
    const MENU_VARIANTS = {
        "shawarma-wrap": {
            name: "Shawarma Wrap",
            description: "Classic shawarma wrap. Choose meat, size and spice level.",

            // Generic groups: can reuse pattern for other items
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

            // Single "Spicy" toggle button
            spiceToggle: true,
            startingFrom: 70
        },

        // EXAMPLES for later (uncomment / adapt when you’re ready):
        /*
        "shawarma-rice": {
            name: "Shawarma Rice",
            description: "Rice bowl topped with shawarma and your choice of meat.",
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
                    label: "Serving",
                    options: [
                        { id: "solo",     label: "Solo",     price: 95 },
                        { id: "overload", label: "Overload", price: 150 }
                    ]
                }
            ],
            spiceToggle: true,
            startingFrom: 95
        },

        "drinks": {
            name: "Beverages",
            description: "Refreshments to go with your shawarma.",
            // Simple flat options (no groups)
            options: [
                { id: "plum-regular", label: "Plum Tea Regular", price: 60 },
                { id: "plum-large",   label: "Plum Tea Large",   price: 70 },
                { id: "coke-mismo",   label: "Coke Mismo",       price: 25 }
            ],
            startingFrom: 25
        }
        */
    };

    const variantButtons = document.querySelectorAll(".js-open-variants");
    const variantModalEl = document.getElementById("variantModal");

    if (variantButtons.length && variantModalEl && typeof bootstrap !== "undefined") {
        const modalTitle = variantModalEl.querySelector("#variantModalLabel");
        const modalDesc = variantModalEl.querySelector("#variantModalDescription");
        const optionsContainer = variantModalEl.querySelector("#variantModalOptions");
        const priceHint = variantModalEl.querySelector("#variantModalPriceHint");

        const variantModal = new bootstrap.Modal(variantModalEl);

        variantButtons.forEach((btn) => {
            btn.addEventListener("click", () => {
                const key = btn.getAttribute("data-product-id");
                const product = MENU_VARIANTS[key];
                if (!product) return;

                // Set title + description
                modalTitle.textContent = product.name;
                modalDesc.textContent = product.description || "";

                // Clear previous options
                optionsContainer.innerHTML = "";

                // =============== GROUPED PRODUCTS (meat/size/etc.) ===============
                if (product.groups && product.groups.length) {
                    const selections = {}; // e.g., { meat: opt, size: opt, ... }
                    let isSpicy = false;   // single toggle

                    const getSizeSelection = () => {
                        // any group marked as key === "size"
                        return selections.size || null;
                    };

                    const updateHint = () => {
                        const sizeSel = getSizeSelection();
                        if (!sizeSel) {
                            priceHint.textContent = "Pick your options to see the price.";
                            return;
                        }

                        const parts = [];

                        // include meat if it exists and is selected
                        if (selections.meat) {
                            parts.push(selections.meat.label);
                        }

                        // include any other group labels if you want later
                        // but for now we only show meat + size

                        parts.push(sizeSel.label);

                        if (product.spiceToggle && isSpicy) {
                            parts.push("Spicy");
                        }

                        const text = `${parts.join(" • ")} – ₱${sizeSel.price}`;
                        priceHint.textContent = text;
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

                            if (group.key === "size") {
                                optionBtn.innerHTML = `
                                    <span>${opt.label}</span>
                                    <span class="fw-semibold price-tag mb-0">₱${opt.price}</span>
                                `;
                            } else {
                                optionBtn.innerHTML = `<span>${opt.label}</span>`;
                            }

                            optionBtn.addEventListener("click", () => {
                                // remove active only inside this group
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

                    // Build all defined groups (meat, size, etc.)
                    product.groups.forEach(makeGroupSection);

                    // === SPICE SINGLE-TOGGLE BUTTON (OPTIONAL) ===
                    if (product.spiceToggle) {
                        const spiceSection = document.createElement("div");
                        spiceSection.className = "mb-2";

                        const spiceHeading = document.createElement("h6");
                        spiceHeading.className = "mb-2 small text-uppercase text-muted";
                        spiceHeading.textContent = "Spice Level";
                        spiceSection.appendChild(spiceHeading);

                        const spiceToggle = document.createElement("button");
                        spiceToggle.type = "button";
                        spiceToggle.className = "list-group-item list-group-item-action text-start";
                        spiceToggle.textContent = "Spicy";

                        spiceToggle.addEventListener("click", () => {
                            isSpicy = !isSpicy;
                            if (isSpicy) {
                                spiceToggle.classList.add("active");
                            } else {
                                spiceToggle.classList.remove("active");
                            }
                            updateHint();
                        });

                        spiceSection.appendChild(spiceToggle);
                        optionsContainer.appendChild(spiceSection);
                    }

                    // Initial hint
                    updateHint();

                // =============== SIMPLE PRODUCTS (flat options list) ===============
                } else if (product.options) {
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
                                .querySelectorAll(".active")
                                .forEach((el) => el.classList.remove("active"));
                            optionBtn.classList.add("active");

                            priceHint.textContent = `${opt.label} – ₱${opt.price}`;
                        });

                        optionsContainer.appendChild(optionBtn);
                    });

                    priceHint.textContent = "Pick a size/deal to see the price.";
                }

                // Show modal
                variantModal.show();
            });
        });
    }
});
