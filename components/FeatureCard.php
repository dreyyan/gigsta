<?php
$image       = $image ?? '../images/temp.png';
$title       = $title ?? 'Default Title';
$description = $description ?? 'Default description text goes here.';
?>

<div class="info-card">
    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>" style="width: 32px; height: 32px;">
    <div class="info-header">
        <h3><?= htmlspecialchars($title) ?></h3>
        <p><?= htmlspecialchars($description) ?></p>
    </div>
</div>