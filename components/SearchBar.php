<?php
$placeholder = $placeholder ?? 'What service are you looking for?';
$type        = $type ?? 'text';
$rounded     = $rounded ?? false; // true = rounded
$width       = $width ?? '';
$id          = $id ?? 'search-bar';
$buttonId    = $buttonId ?? 'search-button';
$icon        = $icon ?? '../images/search-icon.svg';
?>

<div id="search-bar-container"
    class="<?= $rounded ? 'rounded' : '' ?>"
    style="<?= $width ? 'width:' . htmlspecialchars($width) . ';' : '' ?>"
    >
    <input 
        id="<?= htmlspecialchars($id) ?>" 
        type="<?= htmlspecialchars($type) ?>" 
        placeholder="<?= htmlspecialchars($placeholder) ?>" 
    />
    <button id="<?= htmlspecialchars($buttonId) ?>">
        <img src="<?= htmlspecialchars($icon) ?>" alt="Search"/>
    </button>
</div>