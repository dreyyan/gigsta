<?php
$label = $label ?? 'Menu';
$items = $items ?? [];
$boldFirst = $boldFirst ?? false;
$rightAlign = $rightAlign ?? false;
?>
<div class="header-navigation-link dropdown no-underline <?= $rightAlign ? 'dropdown-right' : '' ?>">
    <!-- Always show only the static label -->
    <span class="dropdown-label">
        <span class="dropdown-static-label"><?= htmlspecialchars($label) ?></span>
        <img src="../images/dropdown-arrow-icon.svg" alt="Dropdown Arrow" class="dropdown-icon">
    </span>

    <div class="dropdown-content">
        <?php foreach ($items as $index => $item): ?>
            <a href="<?= htmlspecialchars($item['href'] ?? '#') ?>"
               style="<?= $boldFirst && $index === 0 ? 'font-weight:700;' : '' ?>">
                <?= htmlspecialchars($item['text'] ?? '') ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const label = dropdown.querySelector('.dropdown-label');

        if (label) {
            label.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('active');
            });
        }
    });

    document.addEventListener('click', function() {
        dropdowns.forEach(d => d.classList.remove('active'));
    });
});
</script>
