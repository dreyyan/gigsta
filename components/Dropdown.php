<?php
$label = $label ?? 'Menu';
$items = $items ?? [];
$boldFirst = $boldFirst ?? false;
$rightAlign = $rightAlign ?? false;
$selectedIndex = $selectedIndex ?? 0;
$isFilter = $isFilter ?? false; // ← NEW
?>

<div class="<?= $isFilter ? 'filter-dropdown' : 'header-navigation-link' ?> dropdown no-underline <?= $rightAlign ? 'dropdown-right' : '' ?>">
<div class="dropdown-label" style="cursor: pointer; user-select: none;">
    <span class="dropdown-static-label"><?= htmlspecialchars($label) ?>:&nbsp;</span>
    <span class="dropdown-selected-value">
        <?= htmlspecialchars($items[$selectedIndex]['text'] ?? 'Select') ?>
    </span>
    <img src="../images/dropdown-arrow-icon.svg" alt="Dropdown Arrow" class="dropdown-icon" style="margin-left: 4px; width: 12px; transition: transform 0.2s;">
</div>
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