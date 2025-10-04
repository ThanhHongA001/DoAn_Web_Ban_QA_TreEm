<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$is_active = $_POST['is_active'] ?? 1;
$failed_attempts = $_POST['failed_attempts'] ?? 0;

if (!$id || !$name || !$email) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
    exit;
}

try {
    // Validate user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Người dùng không tồn tại']);
        exit;
    }

    // Check for duplicate email (excluding current user)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Email đã tồn tại']);
        exit;
    }

    // Hash password only if provided
    $hashed_password = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = COALESCE(?, password), phone = ?, address = ?, is_active = ?, failed_attempts = ? WHERE id = ?");
    $stmt->execute([$name, $email, $hashed_password, $phone, $address, $is_active, $failed_attempts, $id]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>