document.addEventListener("DOMContentLoaded", function() {
    const options = {
        root: null,
        rootMargin: "0px",
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                observer.unobserve(entry.target);
            }
        });
    }, options);

    const elements = document.querySelectorAll(".header__image img");
    elements.forEach(element => {
        element.classList.add("reveal", "reveal-origin-right");
        observer.observe(element);
    });
})