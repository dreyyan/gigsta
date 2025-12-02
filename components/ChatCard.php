<?php
$name = $name ?? 'Unknown';
$id = $id ?? '';
$message = $message ?? '';
$lastTimeSeen = $lastTimeSeen ?? '';
$unreadCount = $unreadCount ?? 0;
?>

<div class="chat-card" data-user-id="<?= htmlspecialchars($id) ?>">
    <div class="chat-card-avatar">
        <img src="/images/chat-profile-placeholder.svg" alt="<?= htmlspecialchars($name) ?>'s avatar">
        <?php if($unreadCount > 0): ?>
            <span class="chat-card-unread"><?= $unreadCount ?></span>
        <?php endif; ?>
    </div>
    <div class="chat-card-content">
        <div class="chat-card-header">
            <span class="chat-card-name"><?= htmlspecialchars($name) ?></span>
            <span class="chat-card-time"><?= htmlspecialchars($lastTimeSeen) ?></span>
        </div>
        <div class="chat-card-message">
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
</div>