<?php
$tags = $tags ?? ['website development', 'architecture & interior design', 'video editing'];
?>

<div class="quick-tags">
    <?php foreach ($tags as $tag): ?>
        <div id="tag-container">
            <span><?= htmlspecialchars($tag) ?></span>
            <img src="../images/tag-arrow-icon.svg"/>
        </div>
    <?php endforeach; ?>
</div>
