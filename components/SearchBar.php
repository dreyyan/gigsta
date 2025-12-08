<?php
$placeholder = $placeholder ?? 'What service are you looking for?';
$type        = $type ?? 'text';
$rounded     = $rounded ?? false; // true = rounded
$width       = $width ?? '';
$id          = $id ?? 'search-bar';
$buttonId    = $buttonId ?? 'search-button';
$icon        = $icon ?? '/images/search-icon.svg';
?>

<form 
    id="search-bar-container"
    class="<?= $rounded ? 'rounded' : '' ?>"
    style="<?= $width ? 'width:' . htmlspecialchars($width) . ';' : '' ?>"
    method="GET"
    action="/pages/BrowseGigs.php"
>
    <input 
        id="<?= htmlspecialchars($id) ?>" 
        name="query"
        type="<?= htmlspecialchars($type) ?>" 
        placeholder="<?= htmlspecialchars($placeholder) ?>" 
        value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>"
    />
    <button id="<?= htmlspecialchars($buttonId) ?>" type="submit">
        <img src="<?= htmlspecialchars($icon) ?>" alt="Search"/>
    </button>
</form>