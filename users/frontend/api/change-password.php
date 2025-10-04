<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'error' => 'Vui lòng đăng nhập']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$current_password = isset($input['current_password']) ? $input['current_password'] : '';
$new_password = isset($input['new_password']) ? $input['new_password'] : '';

if (empty($current_password) || empty($new_password)) {
    echo json_encode(['status' => 'error', 'error' => 'Vui lòng nhập đầy đủ thông tin']);
    exit;
}

if (strlen($new_password) < 8) {
    echo json_encode(['status' => 'error', 'error' => 'Mật khẩu mới phải có ít nhất 8 ký tự']);
    exit;
}

try {
    // Verify current password
    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($current_password, $user['password'])) {
        echo json_encode(['status' => 'error', 'error' => 'Mật khẩu hiện tại không đúng']);
        exit;
    }

    // Update password
    $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->execute([$new_password_hash, $user_id]);

    // Log the action
    $stmt = $pdo->prepare('INSERT INTO user_logs (user_id, action_type, action_description, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$user_id, 'change_password', 'User changed their password']);

    // Invalidate all sessions except the current one
    $stmt = $pdo->prepare('UPDATE user_sessions SET logout_time = NOW() WHERE user_id = ? AND session_token != ?');
    $stmt->execute([$user_id, session_id()]);

    echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Lỗi khi đổi mật khẩu: ' . $e->getMessage()]);
}
?>