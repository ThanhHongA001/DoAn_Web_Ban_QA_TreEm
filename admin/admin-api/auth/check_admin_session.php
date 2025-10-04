<?php
header('Content-Type: application/json');
require_once '../config.php';
session_start();

try {
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['isLoggedIn' => false]);
        exit;
    }

    $admin_id = $_SESSION['admin_id'];

    // Verify admin exists in the database
    $sql = "SELECT id FROM admins WHERE id = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['admin_id' => $admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo json_encode(['isLoggedIn' => true, 'admin_id' => $admin_id]);
    } else {
        // Clear session if admin_id is invalid
        unset($_SESSION['admin_id']);
        echo json_encode(['isLoggedIn' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi: ' . $e->getMessage()]);
}
?>