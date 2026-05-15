-- Mockup Data for pos_system

SET NAMES utf8mb4;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

-- Clean up existing data to prevent duplicates
TRUNCATE TABLE `order_details`;
TRUNCATE TABLE `orders`;
TRUNCATE TABLE `products`;

-- --------------------------------------------------------
-- Mockup Users
-- --------------------------------------------------------
-- Use INSERT IGNORE to avoid duplicate username errors
-- Password for 'user123' is '123456' hashed
INSERT IGNORE INTO `users` (`role_id`, `fullname`, `username`, `password`, `phone`) VALUES
(2, 'สมชาย ขายดี', 'user123', '$2y$10$8.N9XQ3P1M7Z0W1Y2O3P4uR9S8T7U6V5W4X3Y2Z1A0B1C2D3E4F5G', '0991112222');

-- --------------------------------------------------------
-- Mockup Products
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `detail`, `price`, `image`, `is_active`) VALUES
(1, 'ข้าวมันไก่ต้ม', 'ข้าวมันไก่สูตรดั้งเดิม ไก่นุ่ม น้ำจิ้มรสเด็ด', 50.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(2, 'ข้าวมันไก่ทอด', 'ไก่ทอดกรอบนอกนุ่มใน เสิร์ฟพร้อมน้ำจิ้มหวาน', 55.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(3, 'ข้าวมันไก่ผสม (ต้ม+ทอด)', 'รวมความอร่อยของไก่ต้มและไก่ทอดในจานเดียว', 60.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(4, 'ข้าวมันไก่พิเศษ', 'เพิ่มปริมาณข้าวและไก่แบบจัดเต็ม', 65.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(5, 'ตับไก่เปล่า', 'ตับไก่ต้มสุกกำลังดี นุ่มละมุนลิ้น', 20.00, NULL, 1),
(6, 'ซุปเปอร์ขาไก่', 'ต้มซุปเปอร์รสแซ่บ จัดจ้านถึงใจ', 40.00, NULL, 1),
(7, 'น้ำลำไย', 'หวานเย็นสดชื่น มีเนื้อลำไยเน้นๆ', 15.00, NULL, 1),
(8, 'น้ำเก๊กฮวย', 'แก้กระหาย คลายร้อน หอมกลิ่นเก๊กฮวย', 15.00, NULL, 1);

-- --------------------------------------------------------
-- Mockup Orders (Sample history for the last 3 days)
-- --------------------------------------------------------
-- Order 1: Yesterday
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(100, 1, 'ทานที่ร้าน', 1, 5, 105.00, 200.00, DATE_SUB(NOW(), INTERVAL 1 DAY));
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(100, 1, 1, 50.00, 50.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(100, 2, 1, 55.00, 55.00, DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Order 2: Today
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(101, 1, 'กลับบ้าน', 2, NULL, 130.00, 150.00, NOW());
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(101, 3, 2, 60.00, 120.00, NOW()),
(101, 7, 1, 15.00, 15.00, NOW());

-- Order 3: Two days ago
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(102, 2, 'ทานที่ร้าน', 3, 2, 85.00, 100.00, DATE_SUB(NOW(), INTERVAL 2 DAY));
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(102, 4, 1, 65.00, 65.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(102, 8, 1, 15.00, 15.00, DATE_SUB(NOW(), INTERVAL 2 DAY));

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
