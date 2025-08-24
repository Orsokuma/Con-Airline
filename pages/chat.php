<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../pages/dbconnect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function safeText($text) {
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$userid = intval($_GET['u'] ?? 0);
if (!$userid) {
    die('No user ID provided');
}

$getir = $db->query("SELECT username FROM users WHERE userid=$userid");
if (!$getir || $db->num_rows($getir) === 0) {
    die('User not found.');
}
$ir = $db->fetch_row($getir);
$username = $ir['username'] ?? '';
if (!$username) {
    die('Username missing.');
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function escapeString($db, $string) {
    return addslashes($string);
}

function isMuted($db, $userid) {
    $q = $db->query("SELECT muted_until FROM mutes WHERE user_id = $userid AND muted_until > NOW()");
    return $db->num_rows($q) > 0;
}

if (($_GET['action'] ?? '') === 'fetch_messages') {
    header('Content-Type: application/json');
    ob_clean();
    flush();

    $channel_id = intval($_GET['channel_id'] ?? 0);
    if (!$channel_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing or invalid channel_id']);
        exit;
    }

    $query = $db->query("SELECT m.id, m.message, m.timestamp, u.username FROM messages m JOIN users u ON m.sender_id = u.userid WHERE m.channel_id = $channel_id ORDER BY m.id ASC LIMIT 50");

    if (!$query) {
        http_response_code(500);
        echo json_encode(['error' => 'DB query failed: ' . $db->error]);
        exit;
    }

    $messages = [];
    while ($row = $db->fetch_row($query)) {
        $messages[] = [
            'id' => $row['id'],
            'message' => $row['message'],
            'timestamp' => $row['timestamp'],
            'username' => $row['username']
        ];
    }

    echo json_encode($messages);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'send_message') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
    if (isMuted($db, $userid)) {
        http_response_code(403);
        exit('You are muted and cannot send messages.');
    }
    $channel_id = intval($_POST['channel_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    if ($message === '' || mb_strlen($message) > 500) {
        http_response_code(400);
        exit('Invalid message length.');
    }
    $badWords = ['badword1', 'badword2'];
    foreach ($badWords as $word) {
        if (stripos($message, $word) !== false) {
            http_response_code(400);
            exit('Message contains prohibited words.');
        }
    }
    $safeMessage = escapeString($db, $message);
    $send = $db->query("INSERT INTO messages (channel_id, sender_id, message) VALUES ($channel_id, $userid, '$safeMessage')");
    if (!$send) {
        http_response_code(500);
        exit('Failed to send message.');
    }
    echo "Message sent";
    exit;
}

$channelsResult = $db->query("SELECT id, name FROM channels ORDER BY name ASC");
$channels = [];
while ($row = $db->fetch_row($channelsResult)) {
    if (!isset($row['id']) || !isset($row['name'])) continue;
    $channels[] = [
        'id' => $row['id'],
        'name' => $row['name']
    ];
}

$selectedChannel = $channels[0]['id'] ?? 0;
if (isset($_GET['channel_id'])) {
    $cid = intval($_GET['channel_id']);
    foreach ($channels as $ch) {
        if ($ch['id'] === $cid) {
            $selectedChannel = $cid;
            break;
        }
    }
}
?>

<style>
.chat-container {
    max-width: 800px;
    height: 90vh;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-family: Arial, sans-serif;
    overflow: hidden;
    background-color: #fff;
}

.chat-header {
    padding: 10px;
    border-bottom: 1px solid #ccc;
    background-color: #f5f5f5;
    font-weight: bold;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background: #fafafa;
    font-size: 14px;
}

.chat-form {
    display: flex;
    gap: 10px;
    padding: 10px;
    border-top: 1px solid #ccc;
    background-color: #f9f9f9;
}

.chat-form input[type="text"] {
    flex: 1;
    padding: 8px;
    font-size: 14px;
    border: 1px solid #aaa;
    border-radius: 4px;
}

.chat-form button {
    padding: 8px 16px;
    font-size: 14px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

.chat-form button:hover {
    background-color: #0056b3;
}
</style>

<div class="chat-container">
    <div class="chat-header">
        Logged in as: <strong><?= safeText($username) ?></strong>
        <select id="channel-select" style="float:right;">
            <?php foreach ($channels as $ch): ?>
                <option value="<?= $ch['id'] ?>" <?= ($ch['id'] == $selectedChannel) ? 'selected' : '' ?>>
                    <?= safeText($ch['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="messages" class="chat-messages"></div>

    <form id="chat-form" class="chat-form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="channel_id" id="channel_id" value="<?= $selectedChannel ?>">
        <input type="hidden" name="action" value="send_message">
        <input type="text" id="message-input" name="message" placeholder="Type your message..." autocomplete="off" required maxlength="500">
        <button type="submit">Send</button>
    </form>
</div>

<script>
(() => {
    const messagesDiv = document.getElementById('messages');
    const form = document.getElementById('chat-form');
    const channelSelect = document.getElementById('channel-select');
    const channelInput = document.getElementById('channel_id');
    const messageInput = document.getElementById('message-input');

    let lastMessageId = 0;

    function fetchMessages() {
        const currentChannel = channelSelect.value;
        fetch(`?action=fetch_messages&channel_id=${currentChannel}&u=<?= $userid ?>`)
            .then(r => r.json())
            .then(data => {
                if (!Array.isArray(data)) throw new Error('Invalid message data');
                messagesDiv.innerHTML = '';
                data.forEach(msg => {
                    const div = document.createElement('div');
                    const time = new Date(msg.timestamp).toLocaleTimeString();
                    div.textContent = `[${time}] ${msg.username}: ${msg.message}`;
                    messagesDiv.appendChild(div);
                    lastMessageId = msg.id;
                });
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            })
            .catch(() => {
                messagesDiv.innerHTML = '<div style="color:red;">Failed to load messages.</div>';
            });
    }

    fetchMessages();
    setInterval(fetchMessages, 3000);

    channelSelect.addEventListener('change', () => {
        channelInput.value = channelSelect.value;
        lastMessageId = 0;
        messagesDiv.innerHTML = '';
        fetchMessages();
    });

    form.addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('', {
            method: 'POST',
            body: formData
        }).then(r => {
            if (!r.ok) return r.text().then(text => { throw new Error(text) });
            return r.text();
        }).then(() => {
            messageInput.value = '';
            fetchMessages();
        }).catch(err => {
            alert('Error: ' + err.message);
        });
    });
})();
</script>
