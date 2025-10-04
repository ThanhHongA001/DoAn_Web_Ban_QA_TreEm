<?php
require_once '../dp.php';

header('Content-Type: application/json');



$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$role = $data['role'] ?? 'editor';

if (!$username || !$email || !$password || !in_array($role, ['superadmin', 'editor'])) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Email hoặc tên người dùng đã tồn tại']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password, $role]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>