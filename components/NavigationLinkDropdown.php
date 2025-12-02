<?php
$label = $label ?? 'Menu';
$items = $items ?? [];
?>

<div class="header-navigation-link dropdown no-underline">
    <span class="dropdown-label">
        <?= htmlspecialchars($label) ?>
        <img src="../images/dropdown-arrow-icon.svg" alt="Dropdown Arrow" class="dropdown-icon">
    </span>
    <div class="dropdown-content">
        <?php foreach ($items as $item): ?>
            <a href="<?= htmlspecialchars($item['href'] ?? '#') ?>">
                <?= htmlspecialchars($item['text'] ?? '') ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>