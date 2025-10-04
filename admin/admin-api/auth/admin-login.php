<?php
require_once '../dp.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Create session
        $session_token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("
            INSERT INTO admin_sessions (admin_id, session_token, ip_address, user_agent, login_time)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$admin['id'], $session_token, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_role'] = $admin['role'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email hoặc mật khẩu không đúng']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>