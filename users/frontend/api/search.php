<?php
require_once 'config.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
if (empty($query)) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare('SELECT code, name FROM products WHERE name LIKE ? OR description LIKE ? LIMIT 5');
$stmt->execute(["%$query%", "%$query%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>