<?php
require_once '../dp.php';

header('Content-Type: text/html');

if (!isAdminLoggedIn()) {
    echo '<p>Chưa đăng nhập</p>';
    exit;
}

$id = $_GET['id'] ?? '';

if (!$id) {
    echo '<p>Không tìm thấy đơn hàng</p>';
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ? AND o.status = 'confirmed'");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo '<p>Đơn hàng không tồn tại hoặc chưa được xác nhận</p>';
        exit;
    }

    $stmt = $pdo->prepare("SELECT oi.*, v.size, v.color FROM order_items oi LEFT JOIN product_variants v ON oi.variant_id = v.id WHERE oi.order_id = ?");
    $stmt->execute([$id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<div class="order-details">';
    $html .= '<h2>Chi tiết đơn hàng #' . htmlspecialchars($order['id']) . '</h2>';
    $html .= '<p><strong>Khách hàng:</strong> ' . htmlspecialchars($order['user_name'] ?? 'Không xác định') . '</p>';
    $html .= '<p><strong>Tổng tiền:</strong> ' . number_format($order['total'], 2) . ' VNĐ</p>';
    $html .= '<p><strong>Mã voucher:</strong> ' . htmlspecialchars($order['voucher_code'] ?? 'Không có') . '</p>';
    $html .= '<p><strong>Trạng thái:</strong> ' . htmlspecialchars($order['status']) . '</p>';
    $html .= '<p><strong>Phương thức thanh toán:</strong> ' . htmlspecialchars($order['payment_method'] ?? 'Chưa chọn') . '</p>';
    $html .= '<p><strong>Địa chỉ giao hàng:</strong> ' . htmlspecialchars($order['shipping_address'] ?? '') . '</p>';
    $html .= '<p><strong>Ghi chú:</strong> ' . htmlspecialchars($order['note'] ?? '') . '</p>';
    $html .= '<p><strong>Ngày tạo:</strong> ' . (new DateTime($order['created_at']))->format('d/m/Y H:i:s') . '</p>';
    $html .= '<h3>Sản phẩm</h3>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><th>Sản phẩm</th><th>Kích thước</th><th>Màu sắc</th><th>Giá</th><th>Số lượng</th><th>Tổng</th></tr>';
    foreach ($items as $item) {
        $total = $item['price'] * $item['quantity'];
        $html .= '<tr class="order-item">';
        $html .= '<td>Biến thể #' . htmlspecialchars($item['variant_id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($item['size'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($item['color'] ?? '') . '</td>';
        $html .= '<td>' . number_format($item['price'], 2) . ' VNĐ</td>';
        $html .= '<td>' . htmlspecialchars($item['quantity']) . '</td>';
        $html .= '<td>' . number_format($total, 2) . ' VNĐ</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    $html .= '</div>';

    echo $html;
} catch (PDOException $e) {
    echo '<p>Lỗi hệ thống: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>