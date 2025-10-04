<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $postsPerPage = 6;
    $offset = ($page - 1) * $postsPerPage;

    $sql = "SELECT id, title, summary, content, image, created_at, author_name 
            FROM blog_posts";
    if ($query) {
        $sql .= " WHERE title LIKE :query";
    }
    $sql .= " ORDER BY created_at DESC LIMIT :offset, :postsPerPage";

    $stmt = $pdo->prepare($sql);
    if ($query) {
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':postsPerPage', $postsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countSql = "SELECT COUNT(*) FROM blog_posts";
    if ($query) {
        $countSql .= " WHERE title LIKE :query";
    }
    $countStmt = $pdo->prepare($countSql);
    if ($query) {
        $countStmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalPosts = $countStmt->fetchColumn();

    echo json_encode([
        'posts' => $posts,
        'totalPosts' => $totalPosts,
        'postsPerPage' => $postsPerPage
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>