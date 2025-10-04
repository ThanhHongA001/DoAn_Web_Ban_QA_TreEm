<?php
require_once 'config.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$name = isset($data['name']) ? trim($data['name']) : '';
$address = isset($data['address']) ? trim($data['address']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest_' . session_id();

if (!$name || !$address || !$phone) {
    echo json_encode(['error' => 'Vui lòng điền đầy đủ thông tin']);
    exit;
}

$stmt = $pdo->prepare('
    SELECT ci.variant_id, ci.quantity, p.price, p.discount
    FROM cart_items ci
    JOIN product_variants pv ON ci.variant_id = pv.id
    JOIN products p ON pv.product_code = p.code
    WHERE ci.user_id = ?
');
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
    echo json_encode(['error' => 'Giỏ hàng trống']);
    exit;
}

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * (1 - $item['discount'] / 100) * $item['quantity'];
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare('
        INSERT INTO orders (user_id, name, address, phone, total, status, created_at)
        VALUES (?, ?, ?, ?, ?, "pending", NOW())
    ');
    $stmt->execute([$user_id, $name, $address, $phone, $total]);
    $order_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare('
        INSERT INTO order_items (order_id, variant_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ');
    foreach ($items as $item) {
        $price = $item['price'] * (1 - $item['discount'] / 100);
        $stmt->execute([$order_id, $item['variant_id'], $item['quantity'], $price]);
        $stmt_update = $pdo->prepare('UPDATE product_variants SET stock = stock - ? WHERE id = ?');
        $stmt_update->execute([$item['quantity'], $item['variant_id']]);
    }

    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE user_id = ?');
    $stmt->execute([$user_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
}
?>