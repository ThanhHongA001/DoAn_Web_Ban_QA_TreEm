-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 03, 2025 lúc 02:14 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `susu_kids`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('superadmin','editor') DEFAULT 'editor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin123', 'admin@example.com', '$2y$10$yEgsBl/blukoJsoAKiDQiuvFuxyzUBnT5DUjyx8zyurxorZJGBaA2', 'superadmin'),
(2, 'Cuc123', '09thucuc@gmail.com', '$2y$10$fSmW8AG.yCLZndVSn2MAyOuN2pKcy/dJjGQH1t3mjoeZYG3TjGepa', 'superadmin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_sessions`
--

INSERT INTO `admin_sessions` (`id`, `admin_id`, `session_token`, `ip_address`, `user_agent`, `login_time`, `logout_time`) VALUES
(1, 2, '273c22dd396d51f3bb7347a344b9c8fe1207f2456fbed6e5849edb58a9380dd5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 14:36:17', NULL),
(2, 2, 'ff517897606b0599d457c9a01842192a9244a8e86bb2d16d6dd5ae51bfb906fd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 19:12:37', NULL),
(3, 2, '27b61958106b9a94032835662a95816ace63d96275e4f5632146af9dae6fd94f', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 19:36:39', NULL),
(4, 2, 'c5b6be14e4980d548bb1daa6d8f8c865f713d62cae67a01bbcf0787da76d9c0d', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 20:50:47', NULL),
(5, 2, 'b869fb9a92ab064c8622933c7d3948818f99eb251aed16136228dd3a0b9345d9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 21:14:10', NULL),
(6, 2, '2d37bc35dc9020d0f120aeb2f3336f8c69d3e1f4dc23d099c1706862db4bd378', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 21:22:44', NULL),
(7, 2, '48310c00cb9b5915d7b43b8274abed038d388d83bd88ac56416c63e5eefc04bc', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 23:18:37', NULL),
(8, 2, 'a70e7042ab6689e757ba7e0cd3bd37b7c4e8027da7b256a214c47cc5ec407239', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 14:25:32', NULL),
(9, 2, 'c502f12d5141dd1340de2708f99bdbe97d8485909cdbf0b92fa74bb9701731b7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 21:44:09', NULL),
(10, 2, 'fa8f1191dae445e1ec58ab4ec5a06fafd1defc2d47fc145790223d0793ba38bd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 23:30:50', NULL),
(11, 2, '6912eeb0f493f28ce20222057ff07c1833ca90a4d3a388d5638d74932e113827', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 00:03:34', NULL),
(12, 2, '9987dfd916cd018e85b6bcb0cb4c23ee67c7fc97b1375adfb0d9e19054a45d62', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 00:07:26', NULL),
(13, 2, 'de334e94b1061878cc7d9dead317db8f2cd13e86f0d861f9b4c9f899cb9eff00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 00:13:56', NULL),
(14, 2, 'bc820e123f6de322ef8b28756bf4f7a925e45b96d4016ac82c04327eeccd1b04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 09:02:30', NULL),
(15, 2, '3e12aea2a458ae4246a087fc1448200375c83f983164b12548420ed861b9d3b0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 19:33:24', NULL),
(16, 2, '2e4b333fc1d15ee21c5c325eea711f0929d95a7f0f3a9e156669207d44fa50ea', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 19:36:47', NULL),
(17, 2, 'd116767ae2c32c7df008daa2e0540d37255e2a222f28be760736a6e7757aa71d', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 19:44:10', NULL),
(18, 2, 'fd94640fa34815d10fd544cfa687a271115699021348f218f0bde1f32ab0ab32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-29 12:58:00', NULL),
(19, 2, '093cc93b9f65bb00a12ef29237b758446fc3dcdae2dd638d48f0b0b79805313e', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-29 14:01:22', NULL),
(20, 2, 'cc1e85c6064d008ef057aeba4db870db1d360ce8278e5c4346ce200d129f4151', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-31 11:30:45', NULL),
(21, 2, 'b135f670efbd65eec932415b0c02c4b51fb4ec83ba74f9fdada40a4dd96b18ec', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-08 23:42:21', NULL),
(22, 2, '60de68eaea0325f23cd32ead7cdc1609ff0b3f32596c490dd75fc94a03ada250', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-08 23:48:31', NULL),
(23, 2, 'd2d03cfc4617de3b24bb2fb6b4a2dacc7a3542608d79b18603e7c69216d64521', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-01 22:45:31', NULL),
(24, 2, '8ea47a6896a70d7e05572a56021c2a6f926a4d06b2be2da377e519bbfe18d088', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-01 22:57:32', NULL),
(25, 2, 'fdca2288305750bebb63fc9a3f25047e1c5af2d43d589743f68005900cb40517', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-02 18:08:49', NULL),
(26, 2, '1968ed8d5986a757356c090f67e010ab6368400f2fb505f4ac41deadbfe8aa0c', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-02 23:39:02', NULL),
(27, 2, 'a5bee130eb2a9c7e5f1661430bd3b396038bf38ce4b32b55cae3e613b09ef6ee', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-02 23:42:48', NULL),
(28, 2, '011f1ccc46b32b788dd681cf73b5a346798b7d0c83dd37e87f76c101a358b055', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-03 08:44:48', NULL),
(29, 2, '4d292758ffca08dc2c6841431f96a0e5eb5d16df5a9ce966640b37e414c42f87', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-03 09:33:54', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `author_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `summary`, `content`, `image`, `created_at`, `author_name`) VALUES
(1, 'Cách chọn quần áo cho bé mùa đông', 'Mẹo chọn quần áo ấm áp, thoải mái cho bé trong mùa đông.', '<p>Chọn quần áo mùa đông cho bé cần chú ý đến chất liệu cotton thoáng khí, giữ ấm tốt như len hoặc nỉ. Hãy chọn kích cỡ vừa vặn, không quá chật để bé thoải mái vận động...</p>', 'assets/images/Dong.webp', '2025-08-20 10:00:00', 'Nguyen Thu Cuc'),
(2, 'Top 5 kiểu áo thun bé trai hot 2025', 'Danh sách 5 kiểu áo thun bé trai trendy nhất 2025.', '<p>Khám phá các mẫu áo thun in hình siêu anh hùng, họa tiết hoạt hình, và màu sắc nổi bật đang được yêu thích trong năm 2025...</p>', 'assets/images/Ảnh2.jpg', '2025-08-25 14:30:00', 'Nguyen Thu Cuc'),
(3, 'Cách phối đồ cho bé gái đi học', 'Hướng dẫn phối đồ dễ thương, tiện lợi cho bé đi học.', '<p>Kết hợp váy yếm với áo thun dài tay hoặc quần jeans với áo khoác nhẹ để tạo phong cách năng động, phù hợp cho bé gái đi học...</p>', 'assets/images/OIP.jpg', '2025-08-24 09:00:00', 'Tran Van An'),
(4, 'Bí quyết chọn giày cho bé', 'Cách chọn giày thoải mái, an toàn cho bé.', '<p>Giày cho bé cần có đế mềm, chống trượt và kích cỡ phù hợp để bảo vệ bàn chân nhỏ bé...</p>', 'assets/images/blog/shoes-guide.jpg', '2025-08-23 11:00:00', 'Nguyen Thu Cuc'),
(5, 'Mẹo giữ quần áo bé luôn mới', 'Cách giặt và bảo quản quần áo trẻ em.', '<p>Sử dụng nước giặt dịu nhẹ, tránh phơi trực tiếp dưới nắng gắt để quần áo bé luôn bền màu...</p>', 'assets/images/slider_5.webp', '2025-08-22 15:00:00', 'Tran Van An'),
(6, 'Xu hướng phụ kiện trẻ em 2025', 'Cập nhật các phụ kiện hot cho bé.', '<p>Nón bucket, kính mát mini, và balo in hình động vật đang là xu hướng năm 2025...</p>', 'assets/images/tải xuống.webp', '2025-08-21 12:00:00', 'Nguyen Thu Cuc'),
(7, 'Cách chọn đồ bơi cho bé', 'Hướng dẫn chọn đồ bơi an toàn, dễ thương.', '<p>Đồ bơi cần chống trượt, chất liệu nhanh khô và có màu sắc tươi sáng để bé nổi bật...</p>', 'assets/images/blog/swimwear-guide.jpg', '2025-08-20 16:00:00', 'Tran Van An'),
(8, 'Phong cách dự tiệc cho bé', 'Mẹo chọn trang phục dự tiệc sang trọng.', '<p>Váy công chúa cho bé gái hoặc vest nhỏ cho bé trai sẽ khiến bé tỏa sáng tại các bữa tiệc...</p>', 'assets/images/blog/party-outfits.jpg', '2025-08-19 10:00:00', 'Nguyen Thu Cuc'),
(9, 'Chọn quần áo cho bé sơ sinh', 'Hướng dẫn chọn đồ an toàn cho bé sơ sinh.', '<p>Quần áo cho bé sơ sinh cần 100% cotton, không có khóa kéo hoặc nút sắc nhọn...</p>', 'assets/images/blog/newborn-clothes.jpg', '2025-08-18 08:00:00', 'Tran Van An');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `product_code` varchar(4) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Đầm cho bé gái'),
(2, 'Áo cho bé gái'),
(3, 'Áo thun cho bé trai'),
(4, 'Quần cho bé trai'),
(5, 'Bộ đồ cho bé trai'),
(6, 'Váy cho bé gái'),
(7, 'Áo khoác cho bé gái'),
(8, 'Pijama cho bé gái');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`) VALUES
(1, 'Giá sản phẩm bao nhiêu?', 'Giá sản phẩm tùy thuộc vào từng mặt hàng. Vui lòng kiểm tra trên trang sản phẩm hoặc hỏi tôi tên sản phẩm cụ thể!', '2025-07-08 06:39:19'),
(2, 'Có kích thước nào cho bé?', 'Chúng tôi có các kích thước 1Y,2Y,3Y,...theo bảng đổi size trang Hướng dẫn chọn size cho hầu hết sản phẩm. Vui lòng xem chi tiết sản phẩm để biết thêm.', '2025-07-08 06:39:19'),
(3, 'Giao hàng mất bao lâu?', 'Chúng tôi giao hàng toàn quốc trong 3-5 ngày. Phí giao hàng tùy thuộc vào địa chỉ của bạn.', '2025-07-08 06:39:19'),
(4, 'Có sản phẩm cho bé trai không?', 'Có! Vui lòng xem danh mục \"Bé trai\" trên trang chủ hoặc tìm kiếm sản phẩm cụ thể.', '2025-07-08 06:39:19'),
(5, 'Có sản phẩm cho bé gái không?', 'Có! Vui lòng xem danh mục \"Bé gái\" trên trang chủ hoặc tìm kiếm sản phẩm cụ thể.', '2025-07-08 06:39:19'),
(6, 'Làm sao để liên hệ nhân viên?', 'Bạn có thể nhắn tin qua Zalo bằng link trong chatbox hoặc gọi hotline 0123 456 789.', '2025-07-08 06:39:19'),
(7, 'Shop có địa chỉ ở đâu?', 'Shop SuSu Kids có địa chỉ tại Hoàng Mai, Hà Nội. Bạn cũng có thể đặt hàng trực tuyến.', '2025-10-03 02:31:57'),
(8, 'Thanh toán như thế nào?', 'Chúng tôi hỗ trợ thanh toán khi nhận hàng (COD) hoặc chuyển khoản ngân hàng.', '2025-10-03 02:31:57'),
(9, 'Có miễn phí vận chuyển không?', 'Đơn hàng từ 500.000đ trở lên sẽ được miễn phí vận chuyển toàn quốc.', '2025-10-03 02:31:57'),
(10, 'Có chính sách đổi trả không?', 'Có, bạn có thể đổi trả trong vòng 7 ngày nếu sản phẩm lỗi hoặc không đúng mô tả.', '2025-10-03 02:31:57'),
(11, 'Làm sao theo dõi đơn hàng?', 'Sau khi đặt hàng, bạn sẽ nhận được mã vận đơn. Vào mục “Theo dõi đơn hàng” và nhập mã để kiểm tra.', '2025-10-03 02:31:57'),
(12, 'Shop mở cửa mấy giờ?', 'Shop mở cửa từ 8h00 đến 21h00 tất cả các ngày trong tuần.', '2025-10-03 02:31:57'),
(13, 'Có chương trình khuyến mãi nào không?', 'Hiện tại đang có ưu đãi 20% cho bé yêu và nhiều voucher hấp dẫn. Bạn có thể xem chi tiết ở banner trang chủ.', '2025-10-03 02:31:57'),
(14, 'Sản phẩm có bảo hành không?', 'Một số sản phẩm có bảo hành 3 tháng. Vui lòng kiểm tra chi tiết trong mô tả sản phẩm.', '2025-10-03 02:31:57'),
(15, 'Có hỗ trợ gói quà không?', 'Có! Chúng tôi hỗ trợ gói quà miễn phí khi bạn chọn tuỳ chọn “Gói quà” lúc đặt hàng.', '2025-10-03 02:31:57'),
(16, 'Có ship quốc tế không?', 'Hiện tại shop mới chỉ giao hàng trong Việt Nam. Chúng tôi sẽ mở rộng quốc tế trong thời gian tới.', '2025-10-03 02:31:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sender` enum('user','admin') NOT NULL,
  `media_type` enum('image','video','link') DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `admin_id`, `message`, `sender`, `media_type`, `media_url`, `created_at`) VALUES
(1, 1, NULL, 'Chào shop, mình muốn hỏi về áo thun A1B2.', 'user', NULL, NULL, '2025-08-27 21:59:00'),
(2, 1, 1, 'Chào bạn, áo thun A1B2 còn hàng, bạn muốn size nào?', 'admin', NULL, NULL, '2025-08-27 22:00:00'),
(3, 2, NULL, 'Shop có giao hàng COD không?', 'user', NULL, NULL, '2025-08-27 22:01:00'),
(4, 2, 1, 'Có bạn ơi, shop hỗ trợ COD toàn quốc.', 'admin', NULL, NULL, '2025-08-27 22:02:00'),
(5, 3, NULL, 'Mình muốn đổi sản phẩm, làm thế nào?', 'user', NULL, NULL, '2025-08-27 22:03:00'),
(6, 2, NULL, 'Shop có đó không', 'user', NULL, NULL, '2025-08-27 23:54:25'),
(7, 2, 2, 'Có', 'admin', NULL, NULL, '2025-08-28 20:47:12'),
(8, 4, NULL, NULL, 'user', 'image', 'uploads/sample1.jpg', '2025-08-28 20:52:29'),
(9, 4, 2, 'Chúng tôi có thể giúp gì cho bạn', 'admin', NULL, NULL, '2025-08-28 20:53:25'),
(10, 4, NULL, NULL, 'user', 'video', 'uploads/sample2.mp4', '2025-08-28 20:54:00'),
(11, 4, NULL, 'https://example.com', 'user', 'link', 'https://example.com', '2025-08-28 20:55:00'),
(12, 4, NULL, 'alo', 'user', NULL, NULL, '2025-09-30 21:05:10'),
(16, 4, NULL, 'alo', 'user', NULL, NULL, '2025-09-30 21:34:53'),
(17, 4, NULL, 'alo', 'user', NULL, NULL, '2025-09-30 21:35:10'),
(19, 4, NULL, 'Giá sản phẩm bao nhiêu?', 'user', NULL, NULL, '2025-09-30 21:54:09'),
(20, 4, NULL, 'Giá sản phẩm bao nhiêu?', 'user', NULL, NULL, '2025-10-01 21:49:56'),
(21, 4, NULL, 'Bot: Giá sản phẩm tùy thuộc vào từng mặt hàng. Vui lòng kiểm tra trên trang sản phẩm hoặc hỏi tôi tên sản phẩm cụ thể!', 'admin', NULL, NULL, '2025-10-01 21:49:56'),
(22, 4, NULL, 'Giá sản phẩm bao nhiêu?', 'user', NULL, NULL, '2025-10-01 21:50:00'),
(23, 4, NULL, 'Bot: Giá sản phẩm tùy thuộc vào từng mặt hàng. Vui lòng kiểm tra trên trang sản phẩm hoặc hỏi tôi tên sản phẩm cụ thể!', 'admin', NULL, NULL, '2025-10-01 21:50:00'),
(26, 4, NULL, 'Giá sản phẩm bao nhiêu?', 'user', NULL, NULL, '2025-10-01 21:50:02'),
(27, 4, NULL, 'Bot: Giá sản phẩm tùy thuộc vào từng mặt hàng. Vui lòng kiểm tra trên trang sản phẩm hoặc hỏi tôi tên sản phẩm cụ thể!', 'admin', NULL, NULL, '2025-10-01 21:50:02'),
(28, 4, NULL, 'Giá sản phẩm bao nhiêu?', 'user', NULL, NULL, '2025-10-01 21:50:02'),
(29, 4, NULL, 'Bot: Giá sản phẩm tùy thuộc vào từng mặt hàng. Vui lòng kiểm tra trên trang sản phẩm hoặc hỏi tôi tên sản phẩm cụ thể!', 'admin', NULL, NULL, '2025-10-01 21:50:02'),
(32, 4, NULL, 'Giá sản phẩm bao nhiêu?', 'user', NULL, NULL, '2025-10-01 21:50:03'),
(37, 4, NULL, 'Giá sản phẩm tùy thuộc vào từng mặt hàng. Vui lòng kiểm tra trên trang sản phẩm hoặc hỏi tôi tên sản phẩm cụ thể!', 'admin', NULL, NULL, '2025-10-01 21:52:36'),
(38, 4, NULL, 'https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1NEmr7.img?w=660&h=451&m=6&x=120&y=120&s=280&d=280', 'user', 'link', 'https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1NEmr7.img?w=660&h=451&m=6&x=120&y=120&s=280&d=280', '2025-10-01 22:03:04'),
(39, 1, NULL, 'Có kích thước nào cho bé?', 'user', NULL, NULL, '2025-10-03 08:24:43'),
(40, 1, NULL, 'Chúng tôi có các kích thước S, M, L cho hầu hết sản phẩm. Vui lòng xem chi tiết sản phẩm để biết thêm.', 'admin', NULL, NULL, '2025-10-03 08:24:43'),
(41, 1, NULL, 'Có kích thước nào cho bé?', 'user', NULL, NULL, '2025-10-03 09:02:22'),
(42, 1, NULL, 'Chúng tôi có các kích thước 1Y,2Y,3Y,...theo bảng đổi size trang Hướng dẫn chọn size cho hầu hết sản phẩm. Vui lòng xem chi tiết sản phẩm để biết thêm.', 'admin', NULL, NULL, '2025-10-03 09:02:22'),
(43, 1, NULL, 'Shop có ship COD không?', 'user', NULL, NULL, '2025-10-03 09:16:18'),
(44, 1, NULL, 'Giao hàng mất bao lâu?', 'user', NULL, NULL, '2025-10-03 09:24:59'),
(45, 1, NULL, 'Chúng tôi giao hàng toàn quốc trong 3-5 ngày. Phí giao hàng tùy thuộc vào địa chỉ của bạn.', 'admin', NULL, NULL, '2025-10-03 09:24:59'),
(46, 1, NULL, 'Giao hàng mất bao lâu?', 'user', NULL, NULL, '2025-10-03 10:44:06'),
(47, 1, NULL, 'Chúng tôi giao hàng toàn quốc trong 3-5 ngày. Phí giao hàng tùy thuộc vào địa chỉ của bạn.', 'admin', NULL, NULL, '2025-10-03 10:44:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `voucher_code` varchar(50) DEFAULT NULL,
  `status` enum('pending','confirmed','in transit','shipped','completed','cancelled') DEFAULT 'pending',
  `payment_method` enum('cod','bank_transfer','credit_card') DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `voucher_code`, `status`, `payment_method`, `shipping_address`, `note`, `created_at`) VALUES
(2, 1, 212500.00, '', 'cancelled', 'bank_transfer', 'thucuc11, 0971187020, Tổ 5, Bằng B, Hoàng Liệt, Hoàng Mai, Hà nội', NULL, '2025-07-24 21:18:54'),
(3, 2, 270000.00, '', 'cancelled', 'bank_transfer', 'thucuc11, 0971187020, Tổ 5, Bằng B, Hoàng Liệt, Hà Nội', NULL, '2025-07-24 21:29:01'),
(6, 3, 135000.00, '', 'cancelled', 'cod', 'thucuc09, 0971187020, Tổ 5, Bằng B, Hoàng Liệt, Hoàng Mai, Hà nội', NULL, '2025-07-24 22:25:58'),
(7, 2, 108000.00, '', 'confirmed', 'cod', 'thucuc11, 0911562328, Hoàng Liệt,Hà Nội', NULL, '2025-08-26 14:45:20'),
(8, 2, 108000.00, '', 'pending', 'bank_transfer', 'thucuc11, 0911562328, Hoàng Liệt,Hà Nội', NULL, '2025-08-26 15:07:37'),
(9, 2, 108000.00, '', 'pending', 'cod', 'thucuc11, 0911562328, Hoàng Liệt,Hà Nội', NULL, '2025-08-26 23:05:20'),
(10, 4, 135000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-08-28 22:52:28'),
(11, 4, 135000.00, '', 'pending', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-08-28 22:53:36'),
(12, 4, 135000.00, '', 'pending', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-08-28 23:06:17'),
(13, 4, 243000.00, '', 'pending', 'cod', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-08-28 23:34:05'),
(14, 4, 135000.00, '', 'pending', 'credit_card', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-08-28 23:44:33'),
(15, 4, 135000.00, '', 'cancelled', 'cod', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-08 19:00:49'),
(16, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-15 09:38:51'),
(17, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-15 09:42:28'),
(18, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-15 09:53:18'),
(19, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 08:36:38'),
(20, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 08:45:48'),
(21, 4, 108000.00, '', 'pending', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 08:47:29'),
(22, 4, 108000.00, '', 'cancelled', 'credit_card', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 09:11:33'),
(23, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 09:16:37'),
(24, 4, 108000.00, '', 'cancelled', 'cod', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 09:17:01'),
(25, 4, 108000.00, '', 'cancelled', 'bank_transfer', 'Nguyễn Thùy Chi, 0328709306, dhjsk', NULL, '2025-09-19 09:20:11'),
(26, 1, 108000.00, '', 'pending', 'bank_transfer', 'thucuc09, 0911562328, Hà Nội', NULL, '2025-10-03 10:40:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `price`, `quantity`) VALUES
(1, 7, 1, 108000.00, 1),
(2, 8, 1, 108000.00, 1),
(3, 9, 2, 108000.00, 1),
(4, 10, 3, 135000.00, 1),
(5, 11, 3, 135000.00, 1),
(6, 12, 3, 135000.00, 1),
(7, 13, 3, 135000.00, 1),
(8, 13, 4, 108000.00, 1),
(9, 14, 3, 135000.00, 1),
(10, 15, 3, 135000.00, 1),
(11, 16, 1, 108000.00, 1),
(12, 17, 4, 108000.00, 1),
(13, 18, 2, 108000.00, 1),
(14, 19, 2, 108000.00, 1),
(15, 20, 1, 108000.00, 1),
(16, 21, 4, 108000.00, 1),
(17, 22, 1, 108000.00, 1),
(18, 23, 1, 108000.00, 1),
(19, 24, 1, 108000.00, 1),
(20, 25, 1, 108000.00, 1),
(21, 26, 1, 108000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `code` varchar(4) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `status` tinyint(4) DEFAULT 1,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`code`, `name`, `category_id`, `description`, `image`, `price`, `discount`, `status`, `featured`, `created_at`) VALUES
('5363', 'váy công chúa elsa', 6, 'Váy công chúa elsa đẹp', '[\"assets\\/images\\/68aeb66bd74e6-68a86bb5b2ed3-\\u1ea2nh3.webp\"]', 200000.00, 5.00, 1, 1, '2025-08-27 14:40:27'),
('9WQ7', 'Áo thun phi hành gia', 3, '', '[\"assets\\/images\\/68af209e5fb0f-68af204c6b6ed-68af201409276-anh21.webp\",\"assets\\/images\\/68af209e5fff3-68af204c6b133-68af20140b7af-anh24.webp\"]', 150000.00, 9.99, 1, 1, '2025-08-27 22:13:34'),
('A1B2', 'Áo thun bé trai 1-2 tuổi', 1, 'Áo thun cotton mềm mại, thấm hút tốt, phù hợp cho bé trai 1-2 tuổi.', '[\"assets\\/images\\/68ad6489294f9-68a86dc19fef9-\\u1ea2nh2.webp\",\"assets\\/images\\/68af201409276-anh21.webp\",\"assets\\/images\\/68af20140b7af-anh24.webp\",\"assets\\/images\\/68af204c6b133-68af20140b7af-anh24.webp\",\"assets\\/images\\/68af204c6b6ed-68af201409276-anh21.webp\"]', 120000.00, 10.00, 1, 1, '2025-08-15 14:15:26'),
('C3D4', 'Quần short bé trai 2-3 tuổi', 1, 'Quần short kaki co giãn, thoáng mát, cho bé trai 2-3 tuổi.', '[\"assets\\/images\\/68ad64ac72b66-OIP (1).webp\"]', 150000.00, 5.00, 1, 0, '2025-08-15 14:15:26'),
('E5F6', 'Váy bé gái 4 tuổi', 2, 'Váy xinh xắn cho bé gái 4 tuổi, chất liệu mềm mại, dễ chịu.', '[\"assets\\/images\\/68ada51ff3781-68a86b368e775-68a859a2b9975-\\u1ea2nh4.webp\"]', 200000.00, 0.00, 1, 1, '2025-08-15 14:15:26'),
('LI2D', 'Áo thun bé trai sọc', 3, 'dgdjdk', '[\"assets\\/images\\/68adcb7f4ba9a-68a86c32ef655-\\u1ea2nh1.webp\"]', 150000.00, 10.00, 1, 1, '2025-08-26 21:58:07'),
('MHEE', 'Áo thun bé trai in hình siêu nhân gao', 3, 'dgđk', '[\"assets\\/images\\/68adc74aeb832-68ad658b5851f-sieunhan.jpg\"]', 150000.00, 10.00, 1, 1, '2025-08-26 21:40:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `code` varchar(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `code`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(13, 'A1B2', 1, 5, 'Sản phẩm rất tốt, bé nhà mình mặc vừa vặn và chất vải mềm.', '2025-08-27 14:46:40'),
(14, 'A1B2', 2, 4, 'Hàng đẹp, đóng gói cẩn thận nhưng giao hơi chậm.', '2025-08-25 14:46:40'),
(15, 'A1B2', 3, 3, 'Chất lượng tạm ổn, giá hơi cao so với mong đợi.', '2025-08-22 14:46:40'),
(16, 'A1B2', 1, 5, 'Mình mua tặng bạn, bạn rất thích. Sẽ ủng hộ tiếp.', '2025-08-20 14:46:40'),
(17, 'A1B2', 2, 2, 'Hàng không giống hình lắm, màu hơi nhạt.', '2025-08-17 14:46:40'),
(18, 'A1B2', 1, 4, 'Quần áo mềm, dễ chịu cho bé. Giá cả hợp lý.', '2025-08-27 14:46:40'),
(19, 'LI2D', 1, 5, 'Sản phẩm rất tốt, bé nhà mình mặc vừa vặn và chất vải mềm.', '2025-08-27 16:11:12'),
(20, 'LI2D', 2, 4, 'Hàng đẹp, đóng gói cẩn thận nhưng giao hơi chậm.', '2025-08-25 16:11:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_code` varchar(4) NOT NULL,
  `size` varchar(10) NOT NULL,
  `color` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_code`, `size`, `color`, `stock`, `weight`, `height`) VALUES
(1, 'A1B2', '1y', 'Xanh', 47, NULL, NULL),
(2, 'A1B2', '1y', 'Đỏ', 49, NULL, NULL),
(3, 'LI2D', '2Y', 'Đen', 36, NULL, NULL),
(4, 'A1B2', '5Y', 'Xám', 18, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`) VALUES
(11, 'logo', 'uploads/logo_1759417193.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `failed_attempts` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `last_login`, `is_active`, `failed_attempts`, `created_at`) VALUES
(1, 'thucuc09', '09thucuc@gmail.com', '$2y$10$veylC7Cs33aTABxXfRTMQ.TpfdL7CQslG6mtikvlz4ezL1vNA3Wui', '0911562328', 'Hà Nội', '2025-10-03 08:36:53', 1, 0, '2025-07-13 22:55:42'),
(2, 'thucuc11', '0903cucmia@gmail.com', '$2y$10$FgxOy7RT8HSJ9mrVNT7F6eIKzi4J88DVgGPhyMlpc9fHDnzsxC20a', '0911562328', 'Hoàng Liệt,Hà Nội', '2025-08-28 09:17:42', 1, 5, '2025-07-13 23:07:35'),
(3, 'Nthh1', 'thucuc1@gmail.com', '$2y$10$wcCrPkYy1W7N6Y/aYac5IODv/YSsOFxdGxYQ5tl/40CBtcWyanUH.', '0971187020', 'dhjsk', NULL, 1, 5, '2025-07-14 20:33:09'),
(4, 'Nguyễn Thùy Chi', 'chithuy30092006@gmail.com', '$2y$10$VWGbKlNUcoDJoeYnfUt3DeNeDpb7sPFayfyOAnUtb/rdFb30N4S2K', '0328709306', 'dhjsk', '2025-10-01 22:27:02', 1, 10, '2025-08-28 20:50:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` varchar(100) DEFAULT NULL,
  `action_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `action_type`, `action_description`, `created_at`) VALUES
(1, 2, 'login', 'User logged in successfully', '2025-08-25 23:06:25'),
(2, 2, 'login', 'User logged in successfully', '2025-08-26 08:09:19'),
(3, 2, 'login', 'User logged in successfully', '2025-08-26 14:44:58'),
(4, 2, 'login', 'User logged in successfully', '2025-08-26 22:00:47'),
(5, 2, 'login', 'User logged in successfully', '2025-08-26 23:02:00'),
(6, 2, 'login', 'User logged in successfully', '2025-08-27 20:41:56'),
(7, 2, 'login', 'User logged in successfully', '2025-08-27 20:44:32'),
(8, 2, 'login', 'User logged in successfully', '2025-08-27 21:22:58'),
(9, 2, 'login', 'User logged in successfully', '2025-08-27 22:30:29'),
(10, 2, 'login', 'User logged in successfully', '2025-08-27 23:28:27'),
(11, 2, 'login', 'User logged in successfully', '2025-08-27 23:52:28'),
(12, 2, 'login', 'User logged in successfully', '2025-08-28 00:16:23'),
(13, 2, 'login', 'User logged in successfully', '2025-08-28 09:17:42'),
(14, 4, 'login', 'User logged in successfully', '2025-08-28 20:52:11'),
(15, 4, 'login', 'User logged in successfully', '2025-08-28 22:51:54'),
(16, 4, 'login', 'User logged in successfully', '2025-08-29 12:23:55'),
(17, 4, 'login', 'User logged in successfully', '2025-08-29 13:58:35'),
(18, 4, 'login', 'User logged in successfully', '2025-09-08 18:59:53'),
(19, 4, 'login', 'User logged in successfully', '2025-09-08 18:59:53'),
(20, 4, 'login', 'User logged in successfully', '2025-09-08 23:53:11'),
(21, 4, 'login', 'User logged in successfully', '2025-09-15 09:25:55'),
(22, 4, 'login', 'User logged in successfully', '2025-09-15 11:00:13'),
(23, 4, 'login', 'User logged in successfully', '2025-09-15 20:13:50'),
(24, 4, 'login', 'User logged in successfully', '2025-09-19 08:34:35'),
(25, 4, 'login', 'User logged in successfully', '2025-09-19 10:54:33'),
(26, 4, 'login', 'User logged in successfully', '2025-09-19 18:47:05'),
(27, 4, 'login', 'User logged in successfully', '2025-09-30 15:26:00'),
(28, 4, 'login', 'User logged in successfully', '2025-10-01 21:49:38'),
(29, 4, 'login', 'User logged in successfully', '2025-10-01 22:26:08'),
(30, 4, 'login', 'User logged in successfully', '2025-10-01 22:27:02'),
(31, 1, 'login', 'User logged in successfully', '2025-10-03 00:15:28'),
(32, 1, 'login', 'User logged in successfully', '2025-10-03 08:23:42'),
(33, 1, 'login', 'User logged in successfully', '2025-10-03 08:36:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `session_token`, `ip_address`, `user_agent`, `login_time`, `logout_time`) VALUES
(1, 2, '4f2051dce9d158e130ebd1b87ebaa5fc036bcd343b7b8bf39326ff578669cf6d', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-25 23:06:25', NULL),
(2, 2, 'eadfe3948d5dac09ace72aecdc6fcaf98239a9c49dfb11a729f9af4fc547e66b', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 08:09:19', NULL),
(3, 2, '960fde9b48751c06c2d06e0b4cc91de90055bc3a89a52fb61b008bf3a6c154cb', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 14:44:58', NULL),
(4, 2, '75879a8d32c9c6837d641fbb70fa3c494f16f480d4c38170e187ef6a3aee2d81', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 22:00:47', NULL),
(5, 2, '9bfbb85a001beaf7be6c728045593cfbb7d9e3c20d7a9e1bd029f8049ef9708a', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-26 23:02:00', NULL),
(6, 2, 'ddcc4375c00ff98341017de9bb8d1ca475ef6c9387177e1d45b6094a0f24fd64', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 20:41:56', NULL),
(7, 2, '50135791d58bee1c99e133def36a32bc8afc5ec41911897bb28fab614f03aed5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 20:44:32', NULL),
(8, 2, '807c3893a3dd1a806b9b088bfe62c4369cf9648330f202ebb5a2827931a126f8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 21:22:58', NULL),
(9, 2, '89cbb2dd8a88e0f311bb9d2445c324eeb2e501a14647dcbad149d4b2295724e2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 22:30:29', NULL),
(10, 2, '2210c5d9de300c8a6b935114ba5e991c26bf953c5509af0a42318f82438b33cd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 23:28:27', NULL),
(11, 2, 'e23b7110288930edd9e6bdbb7d166b048b29e1e4d2df3b4fff85f5f189c24684', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-27 23:52:28', NULL),
(12, 2, '07f541c225474fdadb4a0be54a3165104258acb28e05952facbbc9c31ce7773e', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 00:16:23', NULL),
(13, 2, '297a00a3825fe545ca418cebdcd34f4e061c54c67927bc72968e9c09ab0a56b8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 09:17:42', NULL),
(14, 4, '70a757e2d20fcd2e3acdc164c4f85a4ed7748b5f8dd3ecc1d99f8e2117e7c081', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 20:52:11', NULL),
(15, 4, '9ab50bb78cab4fcfb8a2d60af4268b14830f31e88c898240096d6fa27c3b9f04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-28 22:51:54', NULL),
(16, 4, '3ddfc7c83d04996f5793e9b88e545b47ddc9b2218bae3c93070b4a0c677f1a43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-29 12:23:55', NULL),
(17, 4, '5be366abf3eaa836179727b1b12e0d2a96fee49676573d28ebd60bae7e1bd582', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-29 13:58:35', NULL),
(18, 4, '557c5f8c642dbb690ddbc1012af93f7426dbee71d93253da4306a00a8c9117ee', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-08 18:59:53', NULL),
(19, 4, '16ea93ed0a90cc7c3bdbd46d0130e596d5a79bad2ac625dbda9777b72c53f69f', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-08 18:59:53', NULL),
(20, 4, 'e6d7e37bc2a1cb50b32abfd99dcee49223962d6c4ccad6a164bf57a2b78780e6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-08 23:53:11', NULL),
(21, 4, 'fcc03a1f6d01919709c35003400bbf3bbe0c79913bbc74d73377570b2d3d71e8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-15 09:25:55', NULL),
(22, 4, '35d0378b42bc4ac4f4cb457d845014c85f9cfdb7313aff260af3a4215fd20f14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-15 11:00:13', NULL),
(23, 4, 'd90c793a361ccb44ca9089224ae0b264987fca6f4c9a33f3152ce3a3dbc2eb65', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-15 20:13:50', NULL),
(24, 4, '03f1e852e332ee3686321770ba90549d6119f06bdc84ef3a7d1432a742e2338f', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-19 08:34:35', NULL),
(25, 4, '967f98a472e41a168141ed13344e0b57ae3f735983786dd530f4c93c8e66b45f', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-19 10:54:33', NULL),
(26, 4, '139519a899671938cf15fe717f1a8fc604c621c1b32db7d72edeff10c9252184', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-19 18:47:05', NULL),
(27, 4, 'a434d92e62d6e39134f2739162b01c98f52f4d39f4131d89cc9867328fefc212', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-09-30 15:26:00', NULL),
(28, 4, '6f0b15bdde05cb3dd794cd988773d755bd41bcddb243d3f67a0cdd7b614ab0d5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-01 21:49:38', NULL),
(29, 4, '9508821f6914b8362a574610a5f7c95d6f8d3c024603c53ab2524668f060abaf', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-01 22:26:08', NULL),
(30, 4, '03eedc79a44797aa6f6d18137fd325d6d4529dd4e3e69d891644e74cfbcb6474', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-01 22:27:02', NULL),
(31, 1, 'b5cd79cfa85069551c770411ccb9875f44e83f056c3043ece64129e2059e2ec1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-03 00:15:28', NULL),
(32, 1, 'f237361732a796668e7d5099f80f220c6fa7ec6870a5fcb17f4e2eb2239dbdd7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-03 08:23:42', NULL),
(33, 1, '168ca99114a8db27c79fe5dd3ffc2e0338e8d5fe8fa813e79acf9dd894da44f3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', '2025-10-03 08:36:53', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL CHECK (`discount_percent` >= 0 and `discount_percent` <= 100),
  `quantity` int(11) DEFAULT 0,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `description`, `discount_percent`, `quantity`, `expiry_date`) VALUES
(1, '541728', 'voucher giảm 50% cho đơn hàng đầu tiên', 50, 10, '2025-08-31'),
(2, 'ABC123', 'voucher giảm 10% ', 10, 50, '2025-09-30');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_sessions_admin` (`admin_id`);

--
-- Chỉ mục cho bảng `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `cart_items_ibfk_3` (`product_code`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_order` (`order_id`),
  ADD KEY `fk_items_variant` (`variant_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`code`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Chỉ mục cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_product` (`code`),
  ADD KEY `fk_reviews_user` (`user_id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_variants_product` (`product_code`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_logs_user` (`user_id`);

--
-- Chỉ mục cho bảng `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_sessions_user` (`user_id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `admin_sessions`
--
ALTER TABLE `admin_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `fk_admin_sessions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`product_code`) REFERENCES `products` (`code`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`code`) REFERENCES `products` (`code`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_code`) REFERENCES `products` (`code`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `fk_user_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_user_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
