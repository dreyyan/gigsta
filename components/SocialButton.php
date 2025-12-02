<?php
function renderSocialButton($props = []) {
    $label    = $props['label'] ?? 'Click Me';
    $icon     = $props['icon'] ?? '';
    $class    = $props['class'] ?? '';
    $onClick  = $props['onClick'] ?? '';
    $type     = $props['type'] ?? 'button';

    ?>
    <button 
        type="<?= htmlspecialchars($type) ?>" 
        class="social-btn <?= htmlspecialchars($class) ?>"
        <?= $onClick ? "onclick=\"$onClick\"" : '' ?>
    >
        <?php if ($icon): ?>
            <img src="<?= htmlspecialchars($icon) ?>" alt="<?= htmlspecialchars($label) ?>"> 
        <?php endif; ?>
        <?= htmlspecialchars($label) ?>
    </button>
    <?php
}
?>