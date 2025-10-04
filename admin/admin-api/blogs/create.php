<?php
require_once '../dp.php';
header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$summary = $_POST['summary'] ?? '';
$image = $_POST['image'] ?? '';
$author_name = $_POST['author_name'] ?? '';

if (!$title || !$content || !$summary || !$author_name) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, summary, image, created_at, author_name) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([$title, $content, $summary, $image, $author_name]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>