<?php
$tags = $tags ?? ['website development', 'architecture & interior design', 'video editing'];
?>

<div class="quick-tags">
    <?php foreach ($tags as $tag): ?>
        <div class="tag-container" onclick="searchTag('<?= htmlspecialchars($tag, ENT_QUOTES) ?>')">
            <span><?= htmlspecialchars($tag) ?></span>
            <img class="tag-arrow" src="/gigsta/images/tag-arrow-icon.svg" alt="Arrow"/>
        </div>
    <?php endforeach; ?>
</div>

<script>
function searchTag(tag) {
    // Encode tag for URL
    const query = encodeURIComponent(tag);

    // Navigate to BrowseGigs.php with query param
    window.location.href = "/gigsta/pages/BrowseGigs.php?query=" + query;
}
</script>