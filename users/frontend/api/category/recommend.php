<?php
header('Content-Type: application/json');
require_once '../config.php';

$age = (int)($_GET['age'] ?? 3);
$gender = strtolower($_GET['gender'] ?? 'boy'); // boy|girl

// Map danh mục theo giới (dựa theo categories bạn đã cung cấp)
$girlCats = [1,2,6,7,8]; // Đầm, Áo, Váy, Áo khoác, Pijama (bé gái)
$boyCats  = [3,4,5];     // Áo thun, Quần, Bộ đồ (bé trai)
$cats = $gender === 'girl' ? $girlCats : $boyCats;

// Chuẩn hóa size theo tuổi: 1 -> "1Y", v.v.
$targetSize = max(1, min(12, $age)) . 'Y';
$nearSizes = [max(1,$age-1).'Y', $targetSize, min(12,$age+1).'Y'];

try {
    // Lấy sản phẩm trong nhóm danh mục theo giới
    $in = implode(',', array_fill(0, count($cats), '?'));
    $stmt = $pdo->prepare("
        SELECT p.code, p.name, p.description, p.image, p.price, p.discount, p.featured, p.created_at, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.status = 1 AND p.category_id IN ($in)
    ");
    $stmt->execute($cats);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy biến thể
    $variantStmt = $pdo->prepare("
        SELECT size, color, stock FROM product_variants WHERE product_code = ?
    ");

    $now = time();
    $out = [];
    foreach ($rows as $p) {
        // Decode image field if it's JSON and take the first image
        if (!empty($p['image'])) {
            $decoded = json_decode($p['image'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $p['image'] = $decoded[0] ?? null;
            }
        }

        $variantStmt->execute([$p['code']]);
        $variants = $variantStmt->fetchAll(PDO::FETCH_ASSOC);

        // Tính điểm AI
        $sizeScore = 0; $stockScore = 0;
        foreach ($variants as $v) {
            if (in_array($v['size'], $nearSizes, true)) {
                $sizeScore = max($sizeScore, $v['size'] === $targetSize ? 1.0 : 0.6);
            }
            $stockScore = max($stockScore, min(1.0, (int)$v['stock'] / 60.0)); // chuẩn hóa
        }

        $featuredScore = ((int)$p['featured'] === 1) ? 0.6 : 0.0;
        $discountScore = min(0.8, (float)$p['discount'] / 20.0); // 20% => 0.8
        $recencyDays = max(1, (int)round(($now - strtotime($p['created_at'])) / 86400));
        $recencyScore = min(0.8, 14 / $recencyDays); // mới trong 14 ngày được điểm cao

        $aiScore = 0.45*$sizeScore + 0.2*$featuredScore + 0.15*$discountScore + 0.2*$recencyScore + 0.2*$stockScore;

        $p['variants'] = $variants;
        $p['ai_score'] = round($aiScore, 4);
        $out[] = $p;
    }

    // Sắp xếp theo ai_score giảm dần
    usort($out, fn($a,$b) => $b['ai_score'] <=> $a['ai_score']);

    echo json_encode(array_slice($out, 0, 24), JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Recommendation failed']);
}
?>