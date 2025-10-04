<?php
session_start();
header('Content-Type: application/json');

require_once 'api/config.php'; // Database connection configuration

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

// Check if email exists in users table
$stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ? AND is_active = 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Email not found or account inactive']);
    exit;
}

$user = $result->fetch_assoc();

// Generate token
$token = bin2hex(random_bytes(32));
$expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Store token in password_resets table
$stmt = $conn->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, created_at = ?");
$stmt->bind_param("sssss", $email, $token, $expiry, $token, $expiry);
$stmt->execute();

// Send email using PHP mail()
$to = $email;
$subject = "Reset Your Password - SuSu Kids";
$body = "
<html>
<body>
    <h2>Reset Your Password</h2>
    <p>Click the link below to reset your password:</p>
    <a href='http://localhost/susu-kids/reset-password.php?token=$token'>Reset Password</a>
    <p>This link will expire in 1 hour.</p>
</body>
</html>
";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: no-reply@susukids.com" . "\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Reset link sent to your email']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}

$stmt->close();
$conn->close();
?>