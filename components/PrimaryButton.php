<?php
$label = $label ?? 'Click Me';
$class = $class ?? '';
$href = $href ?? '';
?>

<button 
    type="button" 
    class="btn-primary <?= $class ?>" 
    id="primary-btn"
    href="<?= $href ?>"
>
    <?= htmlspecialchars($label) ?>
</button>