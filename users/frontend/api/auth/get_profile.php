<?php
session_start();
require_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

$response = ['isLoggedIn' => false];

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode($response);
        exit;
    }

    $user_id = (int) $_SESSION['user_id'];
    // Chỉ truy những trường cần hiển thị/chỉnh sửa
    $stmt = $pdo->prepare('SELECT id, name, email, phone, address, is_active FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !$user['is_active']) {
        echo json_encode(['isLoggedIn' => false]);
        exit;
    }

    $response = [
        'isLoggedIn' => true,
        'user' => [
            'id' => (int)$user['id'],
            'name' => $user['name'] ?? '',
            'email' => $user['email'] ?? '',
            'phone' => $user['phone'] ?? '',
            'address' => $user['address'] ?? ''
        ]
    ];
    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['isLoggedIn' => false, 'error' => 'Server error']);
}
?>