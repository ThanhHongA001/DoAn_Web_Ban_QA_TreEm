<?php
require_once 'config.php';
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

function getUserId() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
}

if ($action === 'add') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_code = $data['product_code'] ?? '';
    $variant_id = $data['variant_id'] ?? '';
    $quantity = (int)($data['quantity'] ?? 1);
    $user_id = getUserId();

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để đồng bộ giỏ hàng']);
        exit;
    }

    if (!$product_code || !$variant_id || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT stock FROM product_variants WHERE id = ? AND product_code = ?');
    $stmt->execute([$variant_id, $product_code]);
    $variant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$variant || $variant['stock'] < $quantity) {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng vượt quá tồn kho']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE user_id = ? AND variant_id = ? AND product_code = ?');
    $stmt->execute([$user_id, $variant_id, $product_code]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $new_quantity = $cart_item['quantity'] + $quantity;
        if ($new_quantity > $variant['stock']) {
            echo json_encode(['status' => 'error', 'message' => 'Số lượng vượt quá tồn kho']);
            exit;
        }
        $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
        $stmt->execute([$new_quantity, $cart_item['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO cart_items (user_id, variant_id, product_code, quantity) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user_id, $variant_id, $product_code, $quantity]);
    }

    echo json_encode(['status' => 'success']);
}

if ($action === 'list') {
    $user_id = getUserId();
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để xem giỏ hàng']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT ci.id AS cart_id, ci.quantity, ci.product_code AS code, p.name, p.description, p.image, p.price, p.discount, pv.size, pv.color, pv.stock
        FROM cart_items ci
        JOIN product_variants pv ON ci.variant_id = pv.id
        JOIN products p ON ci.product_code = p.code
        WHERE ci.user_id = ?
    ');
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'items' => $items]);
}

if ($action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cart_id = $data['cart_id'] ?? '';
    $quantity = (int)($data['quantity'] ?? 1);
    $user_id = getUserId();

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập']);
        exit;
    }

    if (!$cart_id || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT pv.stock
        FROM cart_items ci
        JOIN product_variants pv ON ci.variant_id = pv.id
        WHERE ci.id = ? AND ci.user_id = ?
    ');
    $stmt->execute([$cart_id, $user_id]);
    $variant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$variant) {
        echo json_encode(['status' => 'error', 'message' => 'Mặt hàng không tồn tại']);
        exit;
    }

    if ($quantity > $variant['stock']) {
        echo json_encode(['status' => 'error', 'message' => 'Số lượng vượt quá tồn kho']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?');
    $stmt->execute([$quantity, $cart_id, $user_id]);

    echo json_encode(['status' => 'success']);
}

if ($action === 'remove') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cart_id = $data['cart_id'] ?? '';
    $user_id = getUserId();

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập']);
        exit;
    }

    if (!$cart_id) {
        echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = ? AND user_id = ?');
    $stmt->execute([$cart_id, $user_id]);

    echo json_encode(['status' => 'success']);
}

if ($action === 'count') {
    $user_id = getUserId();
    if (!$user_id) {
        echo json_encode(['count' => 0]);
        exit;
    }

    $stmt = $pdo->prepare('SELECT SUM(quantity) AS total FROM cart_items WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['count' => (int)($result['total'] ?? 0)]);
}

if ($action === 'checkout') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = getUserId();
    $name = $data['name'] ?? '';
    $phone = $data['phone'] ?? '';
    $address = $data['address'] ?? '';
    $payment_method = $data['payment_method'] ?? '';
    $voucher_code = $data['voucher_code'] ?? '';

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thanh toán']);
        exit;
    }

    if (!$name || !$phone || !$address || !in_array($payment_method, ['cod', 'bank_transfer', 'credit_card'])) {
        echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT ci.id AS cart_id, ci.quantity, p.price, p.discount, pv.id AS variant_id
        FROM cart_items ci
        JOIN product_variants pv ON ci.variant_id = pv.id
        JOIN products p ON ci.product_code = p.code
        WHERE ci.user_id = ?
    ');
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($items)) {
        echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống']);
        exit;
    }

    $total = 0;
    foreach ($items as $item) {
        $price = $item['price'] * (1 - $item['discount'] / 100);
        $total += $price * $item['quantity'];
    }

    if ($voucher_code) {
        $stmt = $pdo->prepare('SELECT discount_percent FROM vouchers WHERE code = ? AND quantity > 0 AND expiry_date >= CURDATE()');
        $stmt->execute([$voucher_code]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($voucher) {
            $total = $total * (1 - $voucher['discount_percent'] / 100);
            $stmt = $pdo->prepare('UPDATE vouchers SET quantity = quantity - 1 WHERE code = ?');
            $stmt->execute([$voucher_code]);
        }
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('
            INSERT INTO orders (user_id, total, voucher_code, status, payment_method, shipping_address)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$user_id, $total, $voucher_code, 'pending', $payment_method, "$name, $phone, $address"]);
        $order_id = $pdo->lastInsertId();

        foreach ($items as $item) {
            $stmt = $pdo->prepare('
                INSERT INTO order_items (order_id, variant_id, price, quantity)
                VALUES (?, ?, ?, ?)
            ');
            $stmt->execute([$order_id, $item['variant_id'], $item['price'] * (1 - $item['discount'] / 100), $item['quantity']]);

            $stmt = $pdo->prepare('UPDATE product_variants SET stock = stock - ? WHERE id = ?');
            $stmt->execute([$item['quantity'], $item['variant_id']]);
        }

        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE user_id = ?');
        $stmt->execute([$user_id]);

        $pdo->commit();
        echo json_encode(['status' => 'success', 'order_id' => $order_id]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
    }
}

if ($action === 'sync') {
    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];
    $user_id = getUserId();

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để đồng bộ giỏ hàng']);
        exit;
    }

    $pdo->beginTransaction();
    try {
        foreach ($items as $item) {
            $product_code = $item['product_code'] ?? '';
            $variant_id = $item['variant_id'] ?? '';
            $quantity = (int)($item['quantity'] ?? 1);

            if (!$product_code || !$variant_id || $quantity <= 0) {
                continue;
            }

            $stmt = $pdo->prepare('SELECT stock FROM product_variants WHERE id = ? AND product_code = ?');
            $stmt->execute([$variant_id, $product_code]);
            $variant = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$variant || $variant['stock'] < $quantity) {
                continue;
            }

            $stmt = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE user_id = ? AND variant_id = ? AND product_code = ?');
            $stmt->execute([$user_id, $variant_id, $product_code]);
            $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart_item) {
                $new_quantity = $cart_item['quantity'] + $quantity;
                if ($new_quantity > $variant['stock']) {
                    continue;
                }
                $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
                $stmt->execute([$new_quantity, $cart_item['id']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO cart_items (user_id, variant_id, product_code, quantity) VALUES (?, ?, ?, ?)');
                $stmt->execute([$user_id, $variant_id, $product_code, $quantity]);
            }
        }
        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi đồng bộ giỏ hàng: ' . $e->getMessage()]);
    }
}
?>