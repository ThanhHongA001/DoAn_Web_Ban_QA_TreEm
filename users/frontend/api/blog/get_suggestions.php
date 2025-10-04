<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $posts = [];

    if ($query) {
        $sql = "SELECT id, title, created_at 
                FROM blog_posts 
                WHERE title LIKE :query 
                ORDER BY created_at DESC 
                LIMIT 5";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($posts);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>