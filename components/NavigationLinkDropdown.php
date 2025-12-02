<?php
$label = $label ?? 'Menu';
$items = $items ?? [];
$boldFirst = $boldFirst ?? false;
$rightAlign = $rightAlign ?? false;
$navMode = $navMode ?? false; // true = navigation, false = selectable
$selectedIndex = $selectedIndex ?? 0;
?>

<div class="header-navigation-link dropdown <?= $rightAlign ? 'dropdown-right' : '' ?>" data-nav-mode="<?= $navMode ? '1' : '0' ?>">
    <span class="dropdown-label">
        <!-- Label always visible -->
        <span class="dropdown-static-label"><?= htmlspecialchars($label) ?>:</span>

        <!-- Show selected value if selectable -->
        <?php if (!$navMode): ?>
            <span class="dropdown-selected-value">
                <?= htmlspecialchars($items[$selectedIndex]['text'] ?? 'Select') ?>
            </span>
        <?php endif; ?>

        <img src="../images/dropdown-arrow-icon.svg" alt="Dropdown Arrow" class="dropdown-icon">
    </span>

    <div class="dropdown-content">
        <?php foreach ($items as $index => $item): ?>
            <a href="<?= htmlspecialchars($item['href'] ?? '#') ?>"
               class="<?= $index === $selectedIndex ? 'selected' : '' ?>"
               data-index="<?= $index ?>"
               style="<?= $boldFirst && $index === 0 ? 'font-weight:700;' : '' ?>">
                <?= htmlspecialchars($item['text'] ?? '') ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>