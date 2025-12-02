<?php
$tags = $tags ?? ['website development', 'architecture & interior design', 'video editing'];
?>

<div class="quick-tags">
    <?php foreach ($tags as $tag): ?>
        <div class="tag-container">
            <span><?= htmlspecialchars($tag) ?></span>
            <img class="tag-arrow" src="../images/tag-arrow-icon.svg" alt="Arrow"/>
        </div>
    <?php endforeach; ?>
</div>
