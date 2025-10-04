<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; // Assumes $pdo is defined here

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_posts':
            $query = trim($_GET['q'] ?? '');
            $page = max(1, (int)($_GET['page'] ?? 1));
            $perPage = 6; // Matches postsPerPage in frontend
            $offset = ($page - 1) * $perPage;

            // Build query for published posts with author name
            $sql = "
    SELECT bp.id, bp.title, bp.summary, bp.content, bp.image, bp.created_at, u.name AS author_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    WHERE bp.status = 'published'
";
            $params = [];

            // Apply search query
            if ($query) {
                $sql .= " AND (bp.title LIKE ? OR bp.summary LIKE ?)";
                $params[] = "%{$query}%";
                $params[] = "%{$query}%";
            }

            // Add pagination
            $sql .= " ORDER BY bp.created_at DESC LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;

            // Fetch posts
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Count total posts for pagination
            $countSql = "
                SELECT COUNT(*) as total
                FROM blog_posts bp
                WHERE bp.status = 'published'
            ";
            if ($query) {
                $countSql .= " AND (bp.title LIKE ? OR bp.summary LIKE ?)";
                $countStmt = $pdo->prepare($countSql);
                $countStmt->execute(["%{$query}%", "%{$query}%"]);
            } else {
                $countStmt = $pdo->query($countSql);
            }
            $totalPosts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            $totalPages = max(1, ceil($totalPosts / $perPage));

            echo json_encode([
                'success' => true,
                'data' => $posts,
                'page' => $page,
                'total_pages' => $totalPages,
                'total_posts' => $totalPosts
            ], JSON_UNESCAPED_UNICODE);
            break;

        case 'get_suggestions':
            $query = trim($_GET['q'] ?? '');
            if (strlen($query) < 2) {
                echo json_encode([]);
                exit;
            }

            $stmt = $pdo->prepare("
                SELECT id, title, created_at
                FROM blog_posts
                WHERE status = 'published' AND title LIKE ?
                ORDER BY created_at DESC
                LIMIT 5
            ");
            $stmt->execute(["%{$query}%"]);
            $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed', 'details' => $e->getMessage()]);
}
?>