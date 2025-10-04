<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    // Get session token from request headers or cookies
    $session_token = isset($_SERVER['HTTP_AUTHORIZATION']) 
        ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) 
        : (isset($_COOKIE['session_token']) ? $_COOKIE['session_token'] : null);

    if (!$session_token) {
        echo json_encode(['is_superadmin' => false, 'error' => 'No session token provided']);
        exit;
    }

    // Query to check admin role based on session token
    $query = "
        SELECT a.role
        FROM admin_sessions s
        JOIN admins a ON s.admin_id = a.id
        WHERE s.session_token = :session_token 
        AND s.logout_time IS NULL
        AND s.login_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':session_token', $session_token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['role'] === 'superadmin') {
        echo json_encode(['is_superadmin' => true]);
    } else {
        echo json_encode(['is_superadmin' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['is_superadmin' => false, 'error' => 'Query failed: ' . $e->getMessage()]);
}
?>