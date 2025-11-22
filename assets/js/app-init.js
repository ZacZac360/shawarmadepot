// app-init.js
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
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // ---------------------- Mobile order bar ----------------------
    const mobileOrderBar = document.querySelector(".mobile-order-bar");
    if (mobileOrderBar) {
        let lastScrollY = window.scrollY;
        window.addEventListener("scroll", () => {
            const currentY = window.scrollY;

            if (currentY > lastScrollY && currentY > 100) {
                mobileOrderBar.classList.add("mobile-order-bar-hidden");
            } else {
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
            { threshold: 0.15 }
        );

        revealEls.forEach((el) => observer.observe(el));
    }
});
