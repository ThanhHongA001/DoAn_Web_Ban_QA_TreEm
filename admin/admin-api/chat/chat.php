<?php
header('Content-Type: application/json');
require_once '../config.php';
session_start();

try {
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['error' => 'Vui lòng đăng nhập với tư cách admin']);
        exit;
    }

    $admin_id = $_SESSION['admin_id'];
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $action = $_GET['action'] ?? '';

        if ($action === 'users') {
            // Fetch users who have sent messages
            $sql = "SELECT DISTINCT u.id, u.name AS username 
                    FROM users u 
                    JOIN messages m ON u.id = m.user_id 
                    ORDER BY u.name ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
        } elseif ($action === 'messages' && isset($_GET['user_id'])) {
            // Fetch messages for a specific user
            $user_id = $_GET['user_id'];
            $sql = "SELECT m.id, m.user_id, m.admin_id, m.message, m.sender, m.created_at 
                    FROM messages m 
                    WHERE m.user_id = :user_id 
                    ORDER BY m.created_at ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($messages);
        } else {
            echo json_encode(['error' => 'Hành động hoặc user_id không hợp lệ']);
        }
    } elseif ($method === 'POST') {
        // Save a new admin message
        $data = json_decode(file_get_contents('php://input'), true);
        $user_id = $data['user_id'] ?? '';
        $message = trim($data['message'] ?? '');

        if (empty($user_id) || empty($message)) {
            echo json_encode(['error' => 'Vui lòng chọn người dùng và nhập tin nhắn']);
            exit;
        }

        // Verify user exists
        $sql = "SELECT id FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['error' => 'Người dùng không tồn tại']);
            exit;
        }

        $sql = "INSERT INTO messages (user_id, admin_id, message, sender) 
                VALUES (:user_id, :admin_id, :message, 'admin')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'admin_id' => $admin_id,
            'message' => $message
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Tin nhắn đã được gửi']);
    } else {
        echo json_encode(['error' => 'Phương thức không được hỗ trợ']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi: ' . $e->getMessage()]);
}
?>