<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta: My Chats</title>
    <style>
        /* Additional styles specific to the chat screen */
        .chats-container {
            display: grid;
            grid-template-columns: 352px 1fr;
            height: calc(100vh - 88px); /* minus header height */
            padding: 0;
            gap: 0;
        }

        .chats-list-container {
            background: var(--background);
            border-right: 2px solid #e0e0e0;
            padding: 40px 24px;
            overflow-y: auto;
        }

        .conversation-container {
            display: flex;
            flex-direction: column;
            background: var(--background);
        }

        .conversation-header-container img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .messages-area {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.4;
            font-family: var(--body);
            font-size: 15px;
        }

        .message.sent {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.received {
            align-self: flex-start;
            background: #e8e8e8;
            color: var(--text);
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
        }

        .message-input-container {
            display: flex;
            padding: 16px 24px;
            background: var(--background);
            border-top: 2px solid #e0e0e0;
            gap: 12px;
        }

        #message-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid var(--primary);
            border-radius: 12px;
            font-family: var(--body);
            font-size: 15px;
            background: var(--background);
            color: var(--text);
        }

        #message-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        #send-button {
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.2s;
        }

        #send-button:hover {
            background: var(--accent);
            transform: scale(1.05);
        }

        /* Placeholder state when no conversation selected */
        .no-conversation {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--neutral);
            font-size: 18px;
            font-family: var(--body);
        }

        .no-conversation img {
            width: 120px;
            opacity: 0.4;
            margin-bottom: 24px;
        }
    </style>
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
                $chatUsers = [
                    ['id' => 1, 'name' => 'Jane Doe',        'message' => 'Thanks! I love the final mix 🔥', 'lastTimeSeen' => '2m ago', 'unreadCount' => 3],
                    ['id' => 2, 'name' => 'John Smith',      'message' => 'Can you send the stems?',       'lastTimeSeen' => '15m ago', 'unreadCount' => 0],
                    ['id' => 3, 'name' => 'Alice Brown',     'message' => 'Payment sent!',                 'lastTimeSeen' => '1h ago', 'unreadCount' => 1],
                    ['id' => 4, 'name' => 'Mike Johnson',    'message' => 'When can you start?',           'lastTimeSeen' => '3h ago', 'unreadCount' => 0],
                    ['id' => 5, 'name' => 'Sarah Williams',  'message' => 'Here’s the reference track',    'lastTimeSeen' => 'Yesterday', 'unreadCount' => 0],
                ];

                foreach ($chatUsers as $user) {
                    $name = $user['name'];
                    $id = $user['id'];
                    $message = $user['message'];
                    $lastTimeSeen = $user['lastTimeSeen'];
                    $unreadCount = $user['unreadCount'];
                    include '../components/ChatCard.php';
                }
                ?>
            </div>
        </div>

        <!-- RIGHT: Conversation View -->
        <div class="conversation-container">
            <div class="conversation-header-container">
                <img src="../images/chat-profile-placeholder.svg" alt="John Doe">
                <div>
                    <h4>John Doe</h4>
                    <small style="color: rgba(255,255,255,0.8);">Online</small>
                </div>
            </div>

            <div class="messages-area">
                <!-- Sample conversation (you’ll replace this with real data later) -->
                <div class="message received">
                    Hey! I just checked the beat you sent. It’s fire! Can you add a little more 808 slide?
                    <div class="message-time">10:32 AM</div>
                </div>
                <div class="message sent">
                    For sure! I’ll get that done in the next hour and send you the updated version.
                    <div class="message-time">10:34 AM</div>
                </div>
                <div class="message received">
                    Perfect, thank you! 🔥
                    <div class="message-time">10:35 AM</div>
                </div>
                <div class="message sent">
                    Updated file sent! Check your email/Dropbox.
                    <div class="message-time">11:02 AM</div>
                </div>
            </div>

            <div class="message-input-container">
                <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                <button id="send-button" title="Send">➤</button>
            </div>
        </div>
    </div>

    <script>
        // Simple interactivity (optional)
        document.getElementById('send-button').addEventListener('click', function() {
            const input = document.getElementById('message-input');
            if (input.value.trim()) {
                const messagesArea = document.querySelector('.messages-area');
                const newMsg = document.createElement('div');
                newMsg.className = 'message sent';
                newMsg.innerHTML = `${input.value}<div class="message-time">${new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</div>`;
                messagesArea.appendChild(newMsg);
                messagesArea.scrollTop = messagesArea.scrollHeight;
                input.value = '';
            }
        });

        document.getElementById('message-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') document.getElementById('send-button').click();
        });

        // Highlight selected chat card
        document.querySelectorAll('.chat-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.chat-card').forEach(c => c.style.backgroundColor = '');
                this.style.backgroundColor = 'var(--primary)';
                this.style.color = 'white';
            });
        });
    </script>
</body>
</html>