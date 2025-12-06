im<?php
function renderInputGroup($props = []) {
    $type        = $props['type'] ?? 'text';
    $name        = $props['name'] ?? '';
    $id          = $props['id'] ?? '';
    $placeholder = $props['placeholder'] ?? '';
    $icon        = $props['icon'] ?? 'https://via.placeholder.com/20x20?text=@';
    $required    = $props['required'] ?? false;
    $class       = $props['class'] ?? '';
    ?>
    <label class="input-group <?= htmlspecialchars($class) ?>">
        <img src="<?= htmlspecialchars($icon) ?>" alt="icon" class="input-icon">
        <input 
            type="<?= htmlspecialchars($type) ?>" 
            name="<?= htmlspecialchars($name) ?>"
            <?= $id ? 'id="' . htmlspecialchars($id) . '"' : '' ?>
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            <?= $required ? 'required' : '' ?>
        >
    </label>
    <?php
}
?>