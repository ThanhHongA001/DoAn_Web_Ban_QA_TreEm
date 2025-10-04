<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=susu_kids;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']);
}

// Function to check if user is superadmin
function isSuperAdmin() {
    return isAdminLoggedIn() && $_SESSION['admin_role'] === 'superadmin';
}
?>