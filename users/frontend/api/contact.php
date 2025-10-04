<?php
header('Content-Type: application/json');
require_once 'config.php';
session_start();

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Vui lòng đăng nhập để gửi hoặc xem tin nhắn']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $method = $_SERVER['REQUEST_METHOD'];
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if ($method === 'GET') {
        // Fetch messages for the user
        $sql = "SELECT m.id, m.user_id, m.admin_id, m.message, m.sender, m.created_at, m.media_type, m.media_url 
                FROM messages m 
                WHERE m.user_id = :user_id 
                ORDER BY m.created_at ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($messages);
    } elseif ($method === 'POST') {
        // Handle message and media upload
        $message = trim($_POST['message'] ?? '');
        $media_type = null;
        $media_url = null;

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['media'];
            $file_type = mime_content_type($file['tmp_name']);
            $allowed_types = ['image/jpeg', 'image/png', 'video/mp4', 'video/webm'];
            if (!in_array($file_type, $allowed_types)) {
                echo json_encode(['error' => 'Định dạng file không được hỗ trợ']);
                exit;
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $media_url = $destination;
                $media_type = strpos($file_type, 'image') === 0 ? 'image' : 'video';
            } else {
                echo json_encode(['error' => 'Lỗi khi tải lên file']);
                exit;
            }
        } elseif ($message && filter_var($message, FILTER_VALIDATE_URL)) {
            $media_type = 'link';
            $media_url = $message;
        }

        if (!$message && !$media_url) {
            echo json_encode(['error' => 'Tin nhắn hoặc file không được để trống']);
            exit;
        }

        // Insert user message
        $sql = "INSERT INTO messages (user_id, message, sender, media_type, media_url) 
                VALUES (:user_id, :message, 'user', :media_type, :media_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'message' => $message,
            'media_type' => $media_type,
            'media_url' => $media_url
        ]);
        $user_message_id = $pdo->lastInsertId();

        // Check for FAQ auto-response
        $auto_response = null;
        if ($message) {
            // Simple keyword matching: Extract keywords from user message
            $keywords = preg_split('/\s+/', strtolower(str_replace(['àáạảãâầấậẩẫăằắặẳẵ', 'èéẹẻẽêềếệểễ'], 'a', $message))); // Normalize Vietnamese accents for simplicity
            $keywords = array_filter($keywords, function($word) { return strlen($word) > 2; });
            
            if (!empty($keywords)) {
                // Search for matching FAQ question
                $keyword_placeholder = implode('%', $keywords);
                $faq_sql = "SELECT question, answer FROM faqs 
                            WHERE LOWER(question) LIKE :keyword 
                            LIMIT 1";
                $faq_stmt = $pdo->prepare($faq_sql);
                $faq_stmt->execute(['keyword' => '%' . $keyword_placeholder . '%']);
                $faq = $faq_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($faq) {
                    // Insert auto-response as admin message
                    $response_sql = "INSERT INTO messages (user_id, message, sender) 
                                     VALUES (:user_id, :response, 'admin')";
                    $response_stmt = $pdo->prepare($response_sql);
                    $response_stmt->execute([
                        'user_id' => $user_id,
                        'response' => $faq['answer']
                    ]);
                    $auto_response = $faq['answer'];
                }
            }
        }

        // Return the latest messages (including auto-response) for real-time update
        $latest_sql = "SELECT m.id, m.user_id, m.admin_id, m.message, m.sender, m.created_at, m.media_type, m.media_url 
                       FROM messages m 
                       WHERE m.user_id = :user_id AND m.id >= :last_id
                       ORDER BY m.created_at ASC";
        $latest_stmt = $pdo->prepare($latest_sql);
        $latest_stmt->execute(['user_id' => $user_id, 'last_id' => $user_message_id]);
        $latest_messages = $latest_stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success', 
            'message' => 'Tin nhắn đã được gửi',
            'auto_response' => $auto_response,
            'latest_messages' => $latest_messages
        ]);
    } else {
        echo json_encode(['error' => 'Phương thức không được hỗ trợ']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi: ' . $e->getMessage()]);
}
?>