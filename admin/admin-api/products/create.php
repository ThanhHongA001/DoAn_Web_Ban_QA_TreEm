<?php
require_once '../dp.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$code = $_POST['code'] ?? '';
$name = $_POST['name'] ?? '';
$category_id = $_POST['category_id'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? 0.00;
$discount = $_POST['discount'] ?? 0.00;
$featured = $_POST['featured'] ?? 0;

if (!$code || !$name || !$category_id || !$price) {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin không đầy đủ']);
    exit;
}

// Validate code length and format
if (strlen($code) !== 4 || !preg_match('/^[A-Z0-9]+$/', $code)) {
    echo json_encode(['status' => 'error', 'message' => 'Mã sản phẩm phải là 4 ký tự chữ và số']);
    exit;
}

// Check for duplicate code with retry
$maxAttempts = 5;
$attempt = 0;
while ($attempt < $maxAttempts) {
    try {
        $stmt = $pdo->prepare("SELECT code FROM products WHERE code = ?");
        $stmt->execute([$code]);
        if ($stmt->fetch()) {
            // Duplicate found, generate new code
            $code = generateRandomCode();
            $attempt++;
            continue;
        }
        break;
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi kiểm tra mã: ' . $e->getMessage()]);
        exit;
    }
}
if ($attempt >= $maxAttempts) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể tạo mã sản phẩm duy nhất']);
    exit;
}

function generateRandomCode() {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < 4; $i++) {
        $code .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $code;
}

// Validate category_id exists
try {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Danh mục không tồn tại']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi kiểm tra danh mục: ' . $e->getMessage()]);
    exit;
}

// Handle multiple image uploads
$images = [];
if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
    // Đường dẫn tuyệt đối tới thư mục lưu ảnh
    $upload_dir = 'D:/Doan/web2/users/frontend/assets/images/';

    // Đảm bảo thư mục tồn tại
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['image']['type'][$key], $allowed_types)) {
                echo json_encode(['status' => 'error', 'message' => 'Chỉ chấp nhận file .jpg, .jpeg, .png, .webp']);
                exit;
            }
            if ($_FILES['image']['size'][$key] > 5 * 1024 * 1024) {
                echo json_encode(['status' => 'error', 'message' => 'Hình ảnh không được vượt quá 5MB']);
                exit;
            }

            // Tạo tên file duy nhất
            $file_name = uniqid() . '-' . basename($_FILES['image']['name'][$key]);
            $file_path = $upload_dir . $file_name;

            // Lưu file
            if (move_uploaded_file($tmp_name, $file_path)) {
                // Chỉ lưu đường dẫn tương đối vào database để hiển thị trên web
                $images[] = 'assets/images/' . $file_name;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi tải lên hình ảnh']);
                exit;
            }
        }
    }
}
$image_json = json_encode($images);

try {
    $stmt = $pdo->prepare("
        INSERT INTO products (code, name, category_id, description, image, price, discount, featured, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$code, $name, $category_id, $description, $image_json, $price, $discount, $featured]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    foreach ($images as $image) {
        if (file_exists('../../' . $image)) {
            unlink('../../' . $image);
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>