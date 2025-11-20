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
    // MOBILE ORDER BAR (optional simple behavior)
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

            // Separate meat, size/deal, and spice selections
            meatOptions: [
                { id: "meat-beef",    label: "Beef" },
                { id: "meat-chicken", label: "Chicken" }
            ],

            sizeOptions: [
                { id: "regular-solo", label: "REGULAR Solo",         price: 70 },
                { id: "regular-b1t1", label: "REGULAR Buy 1 Take 1", price: 135 },
                { id: "large-solo",   label: "LARGE Solo",           price: 85 },
                { id: "large-b1t1",   label: "LARGE Buy 1 Take 1",   price: 165 }
            ],

            spiceOptions: [
                { id: "not-spicy", label: "Not spicy" },
                { id: "spicy",     label: "Spicy" }
            ],

            startingFrom: 70
        }

        // you can add more products here later
        // either with meatOptions/sizeOptions/spiceOptions
        // or the old flat `options: [...]` style
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

                // If this product uses multi-group selection (meat + size + spice)
                if (product.meatOptions && product.sizeOptions && product.spiceOptions) {
                    let selectedMeat   = null;
                    let selectedSize   = null;
                    let selectedSpice  = null;

                    const updateHint = () => {
                        if (selectedSize) {
                            const meatLabel  = selectedMeat  ? selectedMeat.label  : "Choose meat";
                            const spiceLabel = selectedSpice ? selectedSpice.label : "Choose spice";

                            let text = `${meatLabel} • ${selectedSize.label}`;
                            if (selectedSpice) {
                                text += ` • ${spiceLabel}`;
                            }
                            text += ` – ₱${selectedSize.price}`;

                            priceHint.textContent = text;
                        } else {
                            priceHint.textContent = "Pick meat, size/deal and spice level.";
                        }
                    };

                    const makeSection = (title, options, type) => {
                        const section = document.createElement("div");
                        section.className = "mb-3";

                        const heading = document.createElement("h6");
                        heading.className = "mb-2 small text-uppercase text-muted";
                        heading.textContent = title;
                        section.appendChild(heading);

                        const list = document.createElement("div");
                        list.className = "list-group small";

                        options.forEach((opt) => {
                            const optionBtn = document.createElement("button");
                            optionBtn.type = "button";
                            optionBtn.className =
                                "list-group-item list-group-item-action d-flex justify-content-between align-items-center";

                            if (type === "size") {
                                optionBtn.innerHTML = `
                                    <span>${opt.label}</span>
                                    <span class="fw-semibold price-tag mb-0">₱${opt.price}</span>
                                `;
                            } else {
                                optionBtn.innerHTML = `<span>${opt.label}</span>`;
                            }

                            optionBtn.addEventListener("click", () => {
                                list.querySelectorAll(".active").forEach(el => el.classList.remove("active"));
                                optionBtn.classList.add("active");

                                if (type === "meat")  selectedMeat  = opt;
                                if (type === "size")  selectedSize  = opt;
                                if (type === "spice") selectedSpice = opt;

                                updateHint();
                            });

                            list.appendChild(optionBtn);
                        });

                        section.appendChild(list);
                        optionsContainer.appendChild(section);
                    };

                    // Build the 3 sections
                    makeSection("Meat",        product.meatOptions,  "meat");
                    makeSection("Size / Deal", product.sizeOptions,  "size");
                    makeSection("Spice Level", product.spiceOptions, "spice");

                    updateHint();
                } else if (product.options) {
                    // FALLBACK: old flat list behavior
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
