<?php
$label = $label ?? 'Menu';
$items = $items ?? [];
$boldFirst = $boldFirst ?? false;
$rightAlign = $rightAlign ?? false;
$selectedIndex = $selectedIndex ?? 0;
?>

<div class="header-navigation-link dropdown no-underline <?= $rightAlign ? 'dropdown-right' : '' ?>">
    <span class="dropdown-label">
        <span class="dropdown-static-label"><?= htmlspecialchars($label) ?>:&nbsp;</span>
        <span class="dropdown-selected-value"><?= htmlspecialchars($items[$selectedIndex]['text'] ?? 'Select') ?></span>
        <img src="../images/dropdown-arrow-icon.svg" alt="Dropdown Arrow" class="dropdown-icon">
    </span>

    <div class="dropdown-content">
        <?php foreach ($items as $index => $item): ?>
            <a href="<?= htmlspecialchars($item['href'] ?? '#') ?>"
               class="<?= $index === $selectedIndex ? 'selected' : '' ?>"
               style="<?= $boldFirst && $index === 0 ? 'font-weight:700;' : '' ?>">
                <?= htmlspecialchars($item['text'] ?? '') ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- JS for navigation on click -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll(".header-navigation-link.dropdown");

    dropdowns.forEach(dropdown => {
        const label = dropdown.querySelector(".dropdown-selected-value");
        const content = dropdown.querySelector(".dropdown-content");
        const links = content.querySelectorAll("a");

        // Toggle dropdown open/close on click
        dropdown.querySelector(".dropdown-label").addEventListener("click", e => {
            e.stopPropagation();
            content.classList.toggle("open");
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", () => content.classList.remove("open"));

        // Navigate on click and update selected value
        links.forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                const url = link.getAttribute("href");
                label.textContent = link.textContent;

                // Mark selected visually
                links.forEach(l => l.classList.remove("selected"));
                link.classList.add("selected");

                content.classList.remove("open");

                // Navigate to page
                window.location.href = url;
            });
        });
    });
});
</script>
