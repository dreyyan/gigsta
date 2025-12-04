<?php
$label = $label ?? 'Menu';
$items = $items ?? [];
$boldFirst = $boldFirst ?? false;
$rightAlign = $rightAlign ?? false;
$selectedIndex = $selectedIndex ?? 0;
?>

<div class="header-navigation-link dropdown no-underline <?= $rightAlign ? 'dropdown-right' : '' ?>">
    <span class="dropdown-label">
        <span class="dropdown-static-label"><?= htmlspecialchars($label) ?>:</span>
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