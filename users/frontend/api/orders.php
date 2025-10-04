<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'error' => 'Vui lòng đăng nhập']);
    exit;
}

try {
    if ($action === 'list') {
        $stmt = $pdo->prepare('
            SELECT id AS order_id, created_at, total, status
            FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
        ');
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'orders' => $orders]);
    } elseif ($action === 'details') {
        $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

        if ($order_id <= 0) {
            echo json_encode(['status' => 'error', 'error' => 'Mã đơn hàng không hợp lệ']);
            exit;
        }

        $stmt = $pdo->prepare('
            SELECT id AS order_id, created_at, total, status, payment_method, shipping_address, voucher_code, note
            FROM orders
            WHERE id = ? AND user_id = ?
        ');
        $stmt->execute([$order_id, $user_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo json_encode(['status' => 'error', 'error' => 'Không tìm thấy đơn hàng']);
            exit;
        }

        $stmt = $pdo->prepare('
            SELECT oi.quantity, oi.price, p.name, p.image, pv.size, pv.color
            FROM order_items oi
            JOIN product_variants pv ON oi.variant_id = pv.id
            JOIN products p ON pv.product_code = p.code
            WHERE oi.order_id = ?
        ');
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Parse image field if stored as JSON array
        foreach ($items as &$item) {
            $image = json_decode($item['image'], true);
            $item['image'] = is_array($image) ? $image[0] : $item['image'];
        }

        echo json_encode([
            'status' => 'success',
            'order_id' => $order['order_id'],
            'created_at' => $order['created_at'],
            'total' => floatval($order['total']),
            'status' => $order['status'],
            'payment_method' => $order['payment_method'],
            'shipping_address' => $order['shipping_address'],
            'voucher_code' => $order['voucher_code'],
            'note' => $order['note'],
            'items' => $items
        ]);
    } elseif ($action === 'cancel') {
        $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

        if ($order_id <= 0) {
            echo json_encode(['status' => 'error', 'error' => 'Mã đơn hàng không hợp lệ']);
            exit;
        }

        // Check if order exists and is cancellable
        $stmt = $pdo->prepare('
            SELECT status
            FROM orders
            WHERE id = ? AND user_id = ?
        ');
        $stmt->execute([$order_id, $user_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo json_encode(['status' => 'error', 'error' => 'Không tìm thấy đơn hàng']);
            exit;
        }

        if (!in_array($order['status'], ['pending', 'confirmed', 'in transit'])) {
            echo json_encode(['status' => 'error', 'error' => 'Đơn hàng không thể hủy do đã giao hoặc đã hủy']);
            exit;
        }

        // Update order status to cancelled
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ? AND user_id = ?');
        $stmt->execute(['cancelled', $order_id, $user_id]);

        // Restore stock for cancelled order items
        $stmt = $pdo->prepare('
            SELECT variant_id, quantity
            FROM order_items
            WHERE order_id = ?
        ');
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $stmt = $pdo->prepare('
                UPDATE product_variants
                SET stock = stock + ?
                WHERE id = ?
            ');
            $stmt->execute([$item['quantity'], $item['variant_id']]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được hủy']);
    } else {
        echo json_encode(['status' => 'error', 'error' => 'Hành động không hợp lệ']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()]);
}
?>