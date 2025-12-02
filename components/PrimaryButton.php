<?php
$label   = $label ?? 'Click Me';
$class   = $class ?? '';
$href    = $href ?? '';
$onClick = $onClick ?? '';
?>

<button 
    type="button" 
    class="primary-btn <?= htmlspecialchars($class) ?>" 
    id="<?= htmlspecialchars($id ?? 'primary-btn') ?>"
    <?php if ($href): ?>
        onclick="window.location.href='<?= htmlspecialchars($href) ?>'"
    <?php elseif ($onClick): ?>
        onclick="<?= $onClick ?>"
    <?php endif; ?>
>
    <?= htmlspecialchars($label) ?>
</button>