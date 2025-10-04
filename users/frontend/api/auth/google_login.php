<?php
header('Content-Type: application/json');

require 'vendor/autoload.php'; // Include Google API PHP Client Library
use Google\Client;

$client = new Client();
$client->setClientId('YOUR_GOOGLE_CLIENT_ID');
$client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');

$credential = $_POST['credential'] ?? null;

if (!$credential) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin xác thực.']);
    exit;
}

try {
    $payload = $client->verifyIdToken($credential);
    if ($payload) {
        $email = $payload['email'];
        $name = $payload['name'];
        $google_id = $payload['sub'];

        // Database connection (example with PDO)
        $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if user exists
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            // Create new user
            $stmt = $pdo->prepare('INSERT INTO users (email, name, google_id, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$email, $name, $google_id]);
            $user_id = $pdo->lastInsertId();
            $message = 'Tài khoản mới đã được tạo và đăng nhập thành công!';
        } else {
            // Update Google ID if not set
            if (empty($user['google_id'])) {
                $stmt = $pdo->prepare('UPDATE users SET google_id = ? WHERE email = ?');
                $stmt->execute([$google_id, $email]);
            }
            $user_id = $user['id'];
            $message = 'Đăng nhập bằng Google thành công!';
        }

        // Generate session token (example)
        $session_token = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare('INSERT INTO sessions (user_id, token, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$user_id, $session_token]);

        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'session_token' => $session_token
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Xác thực Google không hợp lệ.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>