<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

// Get the gigster we're chatting with
$chatWithId = isset($_GET['with']) ? (int)$_GET['with'] : null;
$chatPartnerName = "Select a chat";
$chatPartnerAvatar = "../images/profile-placeholder-icon.svg";

if ($chatWithId) {
    $stmt = $db->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->bindValue(':id', $chatWithId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($user) {
        $chatPartnerName = $user['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/gigsta/css/chats.css">
    <link rel="stylesheet" href="/gigsta/css/header.css">
    <link rel="stylesheet" href="/gigsta/css/dropdown.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Chat with <?= htmlspecialchars($chatPartnerName) ?></title>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <div class="chats-container">
        <!-- LEFT: Chats List -->
        <div class="chats-list-container">
            <h3>Chats</h3>
            <?php 
                $placeholder = "Search chats..."; 
                $rounded = true; 
                $width = "100%"; 
                include '../components/SearchBar.php'; 
            ?>

            <div class="chats-list-items">
                <?php
                // Replace this later with real DB chats
                $chatUsers = [
                    ['id' => 1, 'name' => 'Jane Doe',       'message' => 'Thanks! I love the final mix', 'time' => '2m ago', 'unread' => 3],
                    ['id' => 2, 'name' => 'John Smith',     'message' => 'Can you send the stems?',      'time' => '15m ago', 'unread' => 0],
                    ['id' => 3, 'name' => 'Alice Brown',    'message' => 'Payment sent!',                'time' => '1h ago', 'unread' => 1],
                    ['id' => 4, 'name' => 'Mike Johnson',   'message' => 'When can you start?',          'time' => '3h ago', 'unread' => 0],
                ];

                foreach ($chatUsers as $user):
                    $active = ($chatWithId && $user['id'] == $chatWithId) ? 'active' : '';
                ?>
                    <div class="chat-card <?= $active ?>" onclick="location.href='Chats.php?with=<?= $user['id'] ?>'">
                        <div class="chat-card-avatar">
                            <img src="../images/profile-placeholder-icon.svg" alt="<?= $user['name'] ?>">
                            <?php if ($user['unread'] > 0): ?>
                                <span class="chat-card-unread"><?= $user['unread'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="chat-card-content">
                            <div class="chat-card-header">
                                <span class="chat-card-name"><?= htmlspecialchars($user['name']) ?></span>
                                <span class="chat-card-time"><?= $user['time'] ?></span>
                            </div>
                            <div class="chat-card-message"><?= htmlspecialchars($user['message']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RIGHT: Active Conversation -->
        <div class="chat-area">
            <?php if (!$chatWithId): ?>
                <div class="empty-chat">
                    <p>Select a chat to start messaging</p>
                </div>
            <?php else: ?>
                <div class="chat-header">
                    <img src="<?= $chatPartnerAvatar ?>" alt="<?= htmlspecialchars($chatPartnerName) ?>">
                    <div><?= htmlspecialchars($chatPartnerName) ?></div>
                </div>

                <div class="messages-area" id="messages-area">
                    <div class="message received">
                        Hey! I saw your gig and I'm interested!
                        <div class="message-time"><?= date('g:i A') ?></div>
                    </div>
                </div>

                <div class="message-input-container">
                    <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                    <button id="send-button">
                        <img src="/gigsta/images/send-icon.svg">
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="/gigsta/js/dropdown.js"></script>
    
    <script>
        const messagesArea = document.getElementById('messages-area');
        if (messagesArea) messagesArea.scrollTop = messagesArea.scrollHeight;

        document.getElementById('send-button')?.addEventListener('click', sendMessage);
        document.getElementById('message-input')?.addEventListener('keypress', e => {
            if (e.key === 'Enter') sendMessage();
        });

        function sendMessage() {
            const input = document.getElementById('message-input');
            const text = input.value.trim();
            if (!text) return;

            const msg = document.createElement('div');
            msg.className = 'message sent';
            msg.innerHTML = `${text}<div class="message-time">${new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</div>`;
            messagesArea.appendChild(msg);
            messagesArea.scrollTop = messagesArea.scrollHeight;
            input.value = '';
        }
    </script>
</body>
</html>