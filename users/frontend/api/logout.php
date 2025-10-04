<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

try {
    session_unset();
    session_destroy();

    echo json_encode(['status' => 'success', 'message' => 'Đăng xuất thành công.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi đăng xuất: ' . $e->getMessage()]);
}
?>