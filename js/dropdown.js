document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll(".header-navigation-link.dropdown");

    dropdowns.forEach(dropdown => {
        const labelSpan = dropdown.querySelector(".dropdown-selected-value");
        const content = dropdown.querySelector(".dropdown-content");
        const links = content.querySelectorAll("a");

        // Toggle dropdown
        dropdown.querySelector(".dropdown-label").addEventListener("click", e => {
            e.stopPropagation();
            content.classList.toggle("open");
        });

        // Close dropdown if clicked outside
        document.addEventListener("click", () => content.classList.remove("open"));

        // Click link: update label & navigate
        links.forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                e.stopPropagation();

                // Update selected text
                labelSpan.textContent = link.textContent;

                // Update selected class
                links.forEach(l => l.classList.remove("selected"));
                link.classList.add("selected");

                // Close dropdown
                content.classList.remove("open");

                // Navigate
                const url = link.getAttribute("href");
                if (url && url !== "#") window.location.href = url;
            });
        });
    });
});
