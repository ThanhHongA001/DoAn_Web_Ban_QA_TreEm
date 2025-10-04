<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Get filter parameters
    $range = isset($_GET['range']) ? $_GET['range'] : 'day';
    $specific_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $specific_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
    $specific_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

    // Initialize response data
    $response = [
        'total_revenue' => 0,
        'total_sold' => 0,
        'total_cancelled' => 0,
        'categories' => [],
        'revenue_by_category' => []
    ];

    // Build the date condition based on range
    $date_condition = '';
    if ($range === 'day') {
        $date_condition = "DATE(o.created_at) = :specific_date";
    } elseif ($range === 'month') {
        $date_condition = "DATE_FORMAT(o.created_at, '%Y-%m') = :specific_month";
    } elseif ($range === 'year') {
        $date_condition = "YEAR(o.created_at) = :specific_year";
    }

    // Query for total revenue and total sold items (excluding cancelled orders)
    $query = "
        SELECT SUM(o.total) as total_revenue, SUM(oi.quantity) as total_sold
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.status != 'cancelled' AND $date_condition
    ";
    $stmt = $pdo->prepare($query);
    if ($range === 'day') {
        $stmt->bindParam(':specific_date', $specific_date);
    } elseif ($range === 'month') {
        $stmt->bindParam(':specific_month', $specific_month);
    } elseif ($range === 'year') {
        $stmt->bindParam(':specific_year', $specific_year);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['total_revenue'] = (float)($result['total_revenue'] ?? 0);
    $response['total_sold'] = (int)($result['total_sold'] ?? 0);

    // Query for total cancelled orders
    $query = "
        SELECT COUNT(*) as total_cancelled
        FROM orders o
        WHERE o.status = 'cancelled' AND $date_condition
    ";
    $stmt = $pdo->prepare($query);
    if ($range === 'day') {
        $stmt->bindParam(':specific_date', $specific_date);
    } elseif ($range === 'month') {
        $stmt->bindParam(':specific_month', $specific_month);
    } elseif ($range === 'year') {
        $stmt->bindParam(':specific_year', $specific_year);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['total_cancelled'] = (int)($result['total_cancelled'] ?? 0);

    // Query for revenue by category
    $query = "
        SELECT c.name as category_name, SUM(oi.price * oi.quantity) as revenue
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN product_variants pv ON oi.variant_id = pv.id
        JOIN products p ON pv.product_code = p.code
        JOIN categories c ON p.category_id = c.id
        WHERE o.status != 'cancelled' AND $date_condition
        GROUP BY c.id, c.name
    ";
    $stmt = $pdo->prepare($query);
    if ($range === 'day') {
        $stmt->bindParam(':specific_date', $specific_date);
    } elseif ($range === 'month') {
        $stmt->bindParam(':specific_month', $specific_month);
    } elseif ($range === 'year') {
        $stmt->bindParam(':specific_year', $specific_year);
    }
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $response['categories'][] = $row['category_name'];
        $response['revenue_by_category'][] = (float)$row['revenue'];
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
?>