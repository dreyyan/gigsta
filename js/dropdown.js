document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll(".dropdown[data-nav-mode='0']");

    dropdowns.forEach(dropdown => {
        const selectedValue = dropdown.querySelector(".dropdown-selected-value");
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
                selectedValue.textContent = link.textContent;

                links.forEach(l => l.classList.remove("selected"));
                link.classList.add("selected");

                content.classList.remove("open");
            });
        });
    });
});