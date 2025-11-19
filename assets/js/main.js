document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-bg-slide");
    if (!slides.length) return;

    // Pick a RANDOM starting slide
    let current = Math.floor(Math.random() * slides.length);
    slides[current].classList.add("active");

    const intervalMs = 5000; // Timing

    setInterval(() => {
        slides[current].classList.remove("active");

        current = (current + 1) % slides.length;

        slides[current].classList.add("active");
    }, intervalMs);
});
