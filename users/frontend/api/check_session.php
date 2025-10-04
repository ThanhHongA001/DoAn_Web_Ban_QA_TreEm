<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$response = ['isLoggedIn' => false];

try {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare('SELECT id, name, email, phone, address FROM users WHERE id = ? AND is_active = 1');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $response = [
                'isLoggedIn' => true,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'] ?? '',
                    'email' => $user['email'] ?? '',
                    'phone' => $user['phone'] ?? '',
                    'address' => $user['address'] ?? ''
                ]
            ];
        }
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['isLoggedIn' => false, 'error' => 'Lỗi khi kiểm tra phiên: ' . $e->getMessage()]);
}
?>