<?php
session_start();
require_once '../config.php';

// Hàm tạo token bảo mật
function generateSessionToken() {
    return bin2hex(random_bytes(32));
}

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$email = isset($input['email']) ? trim($input['email']) : '';
$password = isset($input['password']) ? trim($input['password']) : '';
$remember_me = isset($input['remember_me']) ? (bool)$input['remember_me'] : false;

$response = [];

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập email và mật khẩu.']);
    exit;
}

// Kiểm tra định dạng email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ.']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, password, is_active, failed_attempts FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Email hoặc mật khẩu không đúng.']);
        exit;
    }

    if (!$user['is_active']) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Tài khoản đã bị khóa.']);
        exit;
    }

    if ($user['failed_attempts'] >= 10) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Tài khoản bị tạm khóa do quá nhiều lần đăng nhập sai.']);
        exit;
    }

    if (password_verify($password, $user['password'])) {
        // Reset số lần sai
        $stmt = $pdo->prepare('UPDATE users SET failed_attempts = 0, last_login = NOW() WHERE id = ?');
        $stmt->execute([$user['id']]);

        // Regenerate session để tránh fixation
        session_regenerate_id(true);

        // Lưu session
        $session_token = generateSessionToken();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['session_token'] = $session_token;

        // Lưu vào DB session (nếu bạn cần)
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $stmt = $pdo->prepare('INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, login_time) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$user['id'], $session_token, $ip_address, $user_agent]);

        // Log hành động
        $stmt = $pdo->prepare('INSERT INTO user_logs (user_id, action_type, action_description, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$user['id'], 'login', 'User logged in successfully']);

        echo json_encode([
            'status' => 'success',
            'message' => 'Đăng nhập thành công.',
            'session_token' => $session_token
        ]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET failed_attempts = failed_attempts + 1 WHERE id = ?');
        $stmt->execute([$user['id']]);

        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Email hoặc mật khẩu không đúng.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>