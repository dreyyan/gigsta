document.addEventListener("DOMContentLoaded", () => {

    // Existing dropdowns (like Explore)
    const dropdowns = document.querySelectorAll(".header-navigation-link.dropdown");
    dropdowns.forEach(dropdown => {
        const labelSpan = dropdown.querySelector(".dropdown-selected-value");
        const content = dropdown.querySelector(".dropdown-content");
        const links = content.querySelectorAll("a");

        dropdown.querySelector(".dropdown-label").addEventListener("click", e => {
            e.stopPropagation();
            content.classList.toggle("open");
        });

        document.addEventListener("click", () => content.classList.remove("open"));

        links.forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                e.stopPropagation();
                labelSpan.textContent = link.textContent;
                links.forEach(l => l.classList.remove("selected"));
                link.classList.add("selected");
                content.classList.remove("open");
                const url = link.getAttribute("href");
                if (url && url !== "#") window.location.href = url;
            });
        });
    });

    // Profile dropdown fix
    const profileDropdown = document.getElementById("profileDropdown");
    if (profileDropdown) {
        const content = profileDropdown.querySelector(".dropdown-content");

        profileDropdown.addEventListener("click", e => {
            e.stopPropagation();
            profileDropdown.classList.toggle("active"); // toggle active class to show/hide
        });

        document.addEventListener("click", () => {
            profileDropdown.classList.remove("active"); // click outside closes it
        });
    }

});
