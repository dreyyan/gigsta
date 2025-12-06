// dropdown.js — FINAL VERSION (WORKS WITH FILTERS + PROFILE DROPDOWN)
document.addEventListener("DOMContentLoaded", () => {

    // ========================================
    // 1. FILTER DROPDOWNS (Budget, Delivery, Sort By)
    // ========================================
    document.querySelectorAll(".filter-dropdown.dropdown").forEach(dropdown => {
        const label = dropdown.querySelector(".dropdown-label");
        const content = dropdown.querySelector(".dropdown-content");
        const selectedValue = dropdown.querySelector(".dropdown-selected-value");

        if (!label || !content || !selectedValue) return;

        // Open/close on label click
        label.addEventListener("click", e => {
            e.stopPropagation();
            const isOpen = dropdown.classList.contains("open");

            // Close all dropdowns first
            document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("open"));

            // Open this one if it wasn't already
            if (!isOpen) {
                dropdown.classList.add("open");
            }
        });

        // Item click → update text + navigate
        content.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                const text = link.textContent.trim();
                const href = link.getAttribute("href");

                selectedValue.textContent = text;
                content.querySelectorAll("a").forEach(a => a.classList.remove("selected"));
                link.classList.add("selected");
                dropdown.classList.remove("open");

                if (href && href !== "#" && href !== "javascript:void(0)") {
                    window.location.href = href;
                }
            });
        });
    });

    // ========================================
    // 2. PROFILE DROPDOWN (header-profile-container)
    // ========================================
    const profileDropdown = document.getElementById("profileDropdown");

    if (profileDropdown) {
        const trigger = profileDropdown.querySelector(".profile-trigger") || profileDropdown;

        trigger.addEventListener("click", e => {
            e.stopPropagation();
            profileDropdown.classList.toggle("active");
        });

        // Close when clicking outside
        document.addEventListener("click", () => {
            profileDropdown.classList.remove("active");
        });

        // Prevent closing when clicking inside dropdown
        profileDropdown.addEventListener("click", e => {
            e.stopPropagation();
        });
    }

    // ========================================
    // 3. CLOSE ALL DROPDOWNS ON OUTSIDE CLICK (except profile)
    // ========================================
    document.addEventListener("click", () => {
        document.querySelectorAll(".dropdown").forEach(d => {
            if (!d.closest("#profileDropdown")) {
                d.classList.remove("open");
            }
        });
    });
});