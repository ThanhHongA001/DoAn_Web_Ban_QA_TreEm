<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$range = $_GET['range'] ?? 'daily';
$labels = [];
$revenue_data = [];

try {
    if ($range === 'daily') {
        $stmt = $pdo->query("
            SELECT DATE(created_at) as date, SUM(total) as revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY DATE(created_at)
            ORDER BY date DESC
            LIMIT 7
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $labels = array_column($results, 'date');
        $revenue_data = array_column($results, 'revenue');
    } elseif ($range === 'monthly') {
        $stmt = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY month
            ORDER BY month DESC
            LIMIT 12
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $labels = array_column($results, 'month');
        $revenue_data = array_column($results, 'revenue');
    } elseif ($range === 'yearly') {
        $stmt = $pdo->query("
            SELECT YEAR(created_at) as year, SUM(total) as revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY year
            ORDER BY year DESC
            LIMIT 5
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $labels = array_column($results, 'year');
        $revenue_data = array_column($results, 'revenue');
    }

    $total_revenue = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'completed'")->fetchColumn();
    $total_sold = $pdo->query("SELECT SUM(quantity) FROM order_items o JOIN orders ord ON o.order_id = ord.id WHERE ord.status = 'completed'")->fetchColumn();
    $total_cancelled = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'cancelled'")->fetchColumn();

    echo json_encode([
        'status' => 'success',
        'total_revenue' => number_format($total_revenue ?: 0, 2),
        'total_sold' => $total_sold ?: 0,
        'total_cancelled' => $total_cancelled ?: 0,
        'labels' => $labels,
        'revenue_data' => $revenue_data
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>