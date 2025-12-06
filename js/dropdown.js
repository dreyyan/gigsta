// dropdown.js — FINAL VERSION THAT ACTUALLY WORKS WITH EVERYTHING
document.addEventListener("DOMContentLoaded", () => {

    // 1. HEADER DROPDOWNS (Explore, etc.) — old style
    document.querySelectorAll(".header-navigation-link.dropdown").forEach(dropdown => {
        const label = dropdown.querySelector(".dropdown-label");
        const content = dropdown.querySelector(".dropdown-content");
        const selectedValue = dropdown.querySelector(".dropdown-selected-value");

        if (!label || !content) return;

        label.addEventListener("click", e => {
            e.stopPropagation();
            content.classList.toggle("open");
        });

        content.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                const text = link.textContent.trim();
                const href = link.getAttribute("href");

                if (selectedValue) selectedValue.textContent = text;
                content.querySelectorAll("a").forEach(a => a.classList.remove("selected"));
                link.classList.add("selected");
                content.classList.remove("open");

                if (href && href !== "#") window.location.href = href;
            });
        });
    });

    // 2. FILTER DROPDOWNS (Budget, Delivery Time, Sort By) — new style
    document.querySelectorAll(".dropdown:not(.header-navigation-link *)").forEach(dropdown => {
        const label = dropdown.querySelector(".dropdown-label");
        const content = dropdown.querySelector(".dropdown-content");
        const selectedValue = dropdown.querySelector(".dropdown-selected-value");

        if (!label || !content) return;

        label.addEventListener("click", e => {
            e.stopPropagation();
            // Close ALL dropdowns first
            document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("open"));
            dropdown.classList.add("open");
        });

        content.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                const text = link.textContent.trim();
                const href = link.getAttribute("href");

                if (selectedValue) selectedValue.textContent = text;
                content.querySelectorAll("a").forEach(a => a.classList.remove("selected"));
                link.classList.add("selected");
                dropdown.classList.remove("open");

                if (href && href !== "#") {
                    window.location.href = href;
                }
            });
        });
    });

    // Close all dropdowns when clicking outside
    document.addEventListener("click", () => {
        document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("open"));
    });

    // Profile dropdown (unchanged)
    const profile = document.getElementById("profileDropdown");
    if (profile) {
        profile.addEventListener("click", e => e.stopPropagation() || profile.classList.toggle("active"));
        document.addEventListener("click", () => profile.classList.remove("active"));
    }
});