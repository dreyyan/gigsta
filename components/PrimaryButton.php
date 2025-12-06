<?php
// Reset variables to avoid leftover values from other includes
$label   = $label ?? 'Click Me';
$class   = $class ?? '';
$href    = $href ?? '';
$onClick = $onClick ?? '';

// Detect if icon was passed
$hasIcon = isset($icon);
$icon    = $hasIcon ? $icon : null;

// Only use $icon if it was explicitly provided
$icon    = array_key_exists('icon', get_defined_vars()) ? $icon : '';
?>
<button 
    type="button" 
    class="primary-btn<?= htmlspecialchars($class) ?>" 
    id="<?= htmlspecialchars($id ?? 'primary-btn') ?>"
    <?php if ($href): ?>
        onclick="window.location.href='<?= htmlspecialchars($href) ?>'"
    <?php elseif ($onClick): ?>
        onclick="<?= $onClick ?>"
    <?php endif; ?>
>
    <?= htmlspecialchars($label) ?>
    <?php if ($hasIcon && !empty($icon)): ?>
        <img src="<?= htmlspecialchars($icon) ?>" alt="Icon" class="btn-icon"/>
    <?php endif; ?>
</button>
