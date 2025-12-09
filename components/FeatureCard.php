<?php
$image       = $image ?? '/images/vector-art.jpg';
$title       = $title ?? 'Default Title';
$description = $description ?? 'Default description text goes here.';
?>

<div class="info-card">
    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>" style="width: 32px; height: 32px;">
    <div class="info-card-header">
        <p id="info-card-title"><?= htmlspecialchars($title) ?></p>
        <p id="info-card-description"><?= htmlspecialchars($description) ?></p>
    </div>
</div>