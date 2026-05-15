-- Comprehensive Mockup Data for pos_system
SET NAMES utf8mb4;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

-- Clear existing data
TRUNCATE TABLE `order_details`;
TRUNCATE TABLE `orders`;
TRUNCATE TABLE `products`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `roles`;

-- --------------------------------------------------------
-- 1. Roles
-- --------------------------------------------------------
INSERT INTO `roles` (`id`, `name`) VALUES 
(1, 'admin'), 
(2, 'user');

-- --------------------------------------------------------
-- 2. Users
-- --------------------------------------------------------
-- Password for all is 'admin' or '123456' as appropriate
-- admin: $2y$10$MXobZ2vLHy2FX9PaNlhAs.KkHlBxTaNAC/VeLjDRIKyP0N/m0N/sS
-- user: $2y$10$TPK6kl8uDCOM00BWNaH7Me.dsOsbDTE5NlkueQ6944aZwLqgTZFqa
INSERT INTO `users` (`id`, `role_id`, `fullname`, `username`, `password`, `phone`) VALUES
(1, 1, 'แอดมิน ระบบ', 'admin', '$2y$10$MXobZ2vLHy2FX9PaNlhAs.KkHlBxTaNAC/VeLjDRIKyP0N/m0N/sS', '0888888888'),
(2, 2, 'สมชาย ขายดี', 'user123', '$2y$10$TPK6kl8uDCOM00BWNaH7Me.dsOsbDTE5NlkueQ6944aZwLqgTZFqa', '0991112222'),
(3, 2, 'นันท์นภัส ใจดี', 'nan123', '$2y$10$TPK6kl8uDCOM00BWNaH7Me.dsOsbDTE5NlkueQ6944aZwLqgTZFqa', '0887776666'),
(4, 2, 'วิชัย รักเรียน', 'wichai', '$2y$10$TPK6kl8uDCOM00BWNaH7Me.dsOsbDTE5NlkueQ6944aZwLqgTZFqa', '0812345678');

-- --------------------------------------------------------
-- 3. Products
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `detail`, `price`, `image`, `is_active`) VALUES
(1, 'ข้าวมันไก่ต้ม', 'ข้าวมันไก่สูตรดั้งเดิม ไก่นุ่ม น้ำจิ้มรสเด็ด', 50.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(2, 'ข้าวมันไก่ทอด', 'ไก่ทอดกรอบนอกนุ่มใน เสิร์ฟพร้อมน้ำจิ้มหวาน', 55.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(3, 'ข้าวมันไก่ผสม (ต้ม+ทอด)', 'รวมความอร่อยของไก่ต้มและไก่ทอดในจานเดียว', 60.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(4, 'ข้าวมันไก่พิเศษ', 'เพิ่มปริมาณข้าวและไก่แบบจัดเต็ม', 65.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(5, 'ตับไก่เปล่า', 'ตับไก่ต้มสุกกำลังดี นุ่มละมุนลิ้น', 20.00, NULL, 1),
(6, 'ซุปเปอร์ขาไก่', 'ต้มซุปเปอร์รสแซ่บ จัดจ้านถึงใจ', 40.00, NULL, 1),
(7, 'น้ำลำไย', 'หวานเย็นสดชื่น มีเนื้อลำไยเน้นๆ', 15.00, NULL, 1),
(8, 'น้ำเก๊กฮวย', 'แก้กระหาย คลายร้อน หอมกลิ่นเก๊กฮวย', 15.00, NULL, 1),
(9, 'ข้าวมันไก่เนื้อน่อง', 'เนื้อน่องติดหนัง นุ่มฉ่ำ', 55.00, 'rice-steamed-with-chicken-breast-2-1024x609.jpg', 1),
(10, 'เครื่องในรวม', 'ตับ กึ๋น หัวใจ', 30.00, NULL, 1),
(11, 'น้ำอัดลม', 'โคล่า/น้ำใส/น้ำแดง', 20.00, NULL, 1),
(12, 'ข้าวเปล่า', 'ข้าวมันเปล่าๆ หอมกระเทียมขิง', 15.00, NULL, 1);

-- --------------------------------------------------------
-- 4. Orders & Order Details
-- --------------------------------------------------------

-- Orders for MAY 10 (Last week)
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(1, 2, 'ทานที่ร้าน', 1, 1, 115.00, 200.00, '2026-05-10 11:30:00'),
(2, 3, 'กลับบ้าน', 2, NULL, 50.00, 50.00, '2026-05-10 12:15:00');
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(1, 1, 1, 50.00, 50.00, '2026-05-10 11:30:00'),
(1, 4, 1, 65.00, 65.00, '2026-05-10 11:30:00'),
(2, 1, 1, 50.00, 50.00, '2026-05-10 12:15:00');

-- Orders for MAY 12
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(3, 4, 'ทานที่ร้าน', 1, 4, 160.00, 500.00, '2026-05-12 18:00:00'),
(4, 1, 'กลับบ้าน', 2, NULL, 110.00, 110.00, '2026-05-12 18:45:00');
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(3, 3, 2, 60.00, 120.00, '2026-05-12 18:00:00'),
(3, 6, 1, 40.00, 40.00, '2026-05-12 18:00:00'),
(4, 2, 2, 55.00, 110.00, '2026-05-12 18:45:00');

-- Orders for MAY 14 (Yesterday)
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(5, 2, 'ทานที่ร้าน', 1, 2, 70.00, 100.00, '2026-05-14 12:00:00'),
(6, 3, 'ทานที่ร้าน', 2, 3, 220.00, 300.00, '2026-05-14 13:20:00'),
(7, 4, 'กลับบ้าน', 3, NULL, 150.00, 200.00, '2026-05-14 17:10:00');
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(5, 1, 1, 50.00, 50.00, '2026-05-14 12:00:00'),
(5, 5, 1, 20.00, 20.00, '2026-05-14 12:00:00'),
(6, 4, 2, 65.00, 130.00, '2026-05-14 13:20:00'),
(6, 6, 2, 40.00, 80.00, '2026-05-14 13:20:00'),
(6, 7, 1, 10.00, 10.00, '2026-05-14 13:20:00'),
(7, 9, 2, 55.00, 110.00, '2026-05-14 17:10:00'),
(7, 11, 2, 20.00, 40.00, '2026-05-14 17:10:00');

-- Orders for MAY 15 (Today)
INSERT INTO `orders` (`id`, `user_id`, `order_type`, `queue_number`, `table_number`, `total_amount`, `paid_amount`, `created_at`) VALUES
(8, 2, 'ทานที่ร้าน', 1, 5, 65.00, 100.00, '2026-05-15 08:30:00'),
(9, 3, 'กลับบ้าน', 2, NULL, 300.00, 500.00, '2026-05-15 09:15:00'),
(10, 4, 'ทานที่ร้าน', 3, 1, 125.00, 200.00, '2026-05-15 10:00:00');
INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(8, 9, 1, 55.00, 55.00, '2026-05-15 08:30:00'),
(8, 12, 1, 10.00, 10.00, '2026-05-15 08:30:00'),
(9, 1, 6, 50.00, 300.00, '2026-05-15 09:15:00'),
(10, 3, 2, 60.00, 120.00, '2026-05-15 10:00:00'),
(10, 11, 1, 5.00, 5.00, '2026-05-15 10:00:00');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
