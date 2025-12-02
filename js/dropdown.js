document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach(dropdown => {
        const staticLabel = dropdown.querySelector(".dropdown-static-label");
        const valueSpan = dropdown.querySelector(".dropdown-selected-value");
        const content = dropdown.querySelector(".dropdown-content");
        const links = content.querySelectorAll("a");

        const labelText = staticLabel.textContent.replace(":", "");

        // Toggle dropdown
        dropdown.querySelector(".dropdown-label").addEventListener("click", e => {
            e.stopPropagation();
            content.classList.toggle("open");
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", () => content.classList.remove("open"));

        // Select a value
        links.forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                e.stopPropagation();

                valueSpan.textContent = link.textContent; // Only the selected text
                links.forEach(l => l.classList.remove("selected"));
                link.classList.add("selected");

                content.classList.remove("open");
            });
        });
    });
});
