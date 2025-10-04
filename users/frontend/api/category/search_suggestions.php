<?php
header('Content-Type: application/json');
require_once '../config.php';

$q = trim($_GET['q'] ?? '');
if ($q === '') { echo json_encode([]); exit; }

try {
    $stmt = $pdo->prepare("
        SELECT code, name
        FROM products
        WHERE status = 1 AND name LIKE ?
        ORDER BY featured DESC, created_at DESC
        LIMIT 8
    ");
    $like = "%{$q}%";
    $stmt->execute([$like]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}
?>