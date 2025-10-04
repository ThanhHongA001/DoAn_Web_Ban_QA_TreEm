<?php
session_start();
require_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$phone = trim($input['phone'] ?? '');
$address = trim($input['address'] ?? '');
$current_password = $input['current_password'] ?? '';

if ($current_password === '') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mật khẩu hiện tại.']);
    exit;
}

// Validate cơ bản (server-side)
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ.']);
    exit;
}
if ($phone !== '' && !preg_match('/^(0|\+84)\d{8,11}$/', preg_replace('/\s+/', '', $phone))) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Số điện thoại không hợp lệ.']);
    exit;
}

try {
    $user_id = (int) $_SESSION['user_id'];

    // Lấy mật khẩu để verify
    $stmt = $pdo->prepare('SELECT password, is_active FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !$row['is_active']) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Tài khoản không hợp lệ.']);
        exit;
    }

    if (!password_verify($current_password, $row['password'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu hiện tại không đúng.']);
        exit;
    }

    // Nếu đổi email, xác minh chưa trùng với tài khoản khác
    if ($email !== '') {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Email đã được sử dụng.']);
            exit;
        }
    }

    // Build UPDATE linh hoạt
    $fields = [];
    $params = [];

    if ($name !== '') { $fields[] = 'name = ?'; $params[] = $name; }
    if ($email !== '') { $fields[] = 'email = ?'; $params[] = $email; }
    if ($phone !== '') { $fields[] = 'phone = ?'; $params[] = $phone; }
    if ($address !== '') { $fields[] = 'address = ?'; $params[] = $address; }

    if (empty($fields)) {
        echo json_encode(['status' => 'success', 'message' => 'Không có thay đổi.']);
        exit;
    }

    $fields[] = 'updated_at = NOW()';
    $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
    $params[] = $user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Ghi log
    $stmt = $pdo->prepare('INSERT INTO user_logs (user_id, action_type, action_description, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$user_id, 'profile_update', 'User updated profile']);

    echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống.']);
}
?>