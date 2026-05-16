-- ============================================================
-- Rymo Watch – UPDATED DATABASE (rymowatch)
-- Run this file in phpMyAdmin → rymowatch → SQL tab
-- It adds new tables & updates existing ones safely
-- ============================================================

-- 1. Add contact_us table (for Contact Us form)
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id`        int(11)      NOT NULL AUTO_INCREMENT,
  `name`      varchar(255) NOT NULL,
  `email`     varchar(255) NOT NULL,
  `phone`     varchar(20)  DEFAULT '',
  `subject`   varchar(255) NOT NULL,
  `message`   text         NOT NULL,
  `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2. Add order_date column to book_form (for real monthly/yearly reports)
--    (If it already exists this will be silently skipped in MariaDB)
ALTER TABLE `book_form`
  ADD COLUMN IF NOT EXISTS `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- Backfill existing rows so reports don't show 0 for older orders
UPDATE `book_form` SET `order_date` = NOW() WHERE `order_date` IS NULL;


-- 3. Add status column to book_form (Pending / Processing / Delivered)
ALTER TABLE `book_form`
  ADD COLUMN IF NOT EXISTS `status` varchar(50) NOT NULL DEFAULT 'Delivered';

ALTER TABLE `book_form`
  ADD COLUMN IF NOT EXISTS `order_number` varchar(40) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `customer_username` varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `payment_method` varchar(50) NOT NULL DEFAULT 'Card',
  ADD COLUMN IF NOT EXISTS `payment_status` varchar(50) NOT NULL DEFAULT 'Pending',
  ADD COLUMN IF NOT EXISTS `transaction_ref` varchar(60) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `payment_account` varchar(120) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `subtotal_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `notes` text DEFAULT NULL;

UPDATE `book_form`
SET
  `order_number` = IFNULL(`order_number`, CONCAT('RYM-LEGACY-', `id`)),
  `payment_method` = IFNULL(NULLIF(`payment_method`, ''), 'Card'),
  `payment_status` = IFNULL(NULLIF(`payment_status`, ''), 'Paid'),
  `payment_account` = IFNULL(NULLIF(`payment_account`, ''), CONCAT('Card ', RIGHT(IFNULL(`cardno`, '0000'), 4))),
  `subtotal_amount` = IF(`subtotal_amount` = 0, `wprice`, `subtotal_amount`),
  `grand_total` = IF(`grand_total` = 0, `wprice`, `grand_total`);


-- 4. Add feedback table (new input module)
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `improvement` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 4. Sample contact messages (optional – delete if not needed)
INSERT INTO `contact_us` (`name`, `email`, `phone`, `subject`, `message`) VALUES
('Ravi Kumar',   'ravi@example.com',   '9876543210', 'Order Enquiry',      'When will my Rolex be delivered?'),
('Priya Sharma', 'priya@example.com',  '9123456780', 'Product Information','Is the Sea-Dweller water resistant?'),
('Aditya Rao',   'aditya@example.com', '',           'Return / Refund',    'I want to return the smart watch I ordered.');


-- 5. Sample feedback entries (optional)
INSERT INTO `feedback` (`name`, `email`, `rating`, `module_name`, `message`, `improvement`) VALUES
('Ravi Kumar', 'ravi@example.com', 5, 'Reports', 'The report section is useful and easy to understand.', 'Add PDF export option in the future.'),
('Priya Sharma', 'priya@example.com', 4, 'Order Management', 'Status tracking is clear for admin users.', 'Add SMS or email notifications.');


-- 6. Staff accounts
CREATE TABLE IF NOT EXISTS `staff_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `staff_code` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_code_unique` (`staff_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. Suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `gst_number` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `supply_type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. Catalog products
CREATE TABLE IF NOT EXISTS `catalog_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_level` int(11) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) DEFAULT 'images/featured/1.jpg',
  `show_on_website` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `catalog_products`
  ADD COLUMN IF NOT EXISTS `image_path` varchar(255) DEFAULT 'images/featured/1.jpg',
  ADD COLUMN IF NOT EXISTS `show_on_website` tinyint(1) NOT NULL DEFAULT 1,
  ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1;

-- 9. Inventory entries
CREATE TABLE IF NOT EXISTS `inventory_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_name` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_code` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `reorder_level` int(11) NOT NULL,
  `updated_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 10. Return requests
CREATE TABLE IF NOT EXISTS `return_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `return_reason` text NOT NULL,
  `refund_mode` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 11. Service requests
CREATE TABLE IF NOT EXISTS `service_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `watch_model` varchar(255) NOT NULL,
  `issue_type` varchar(100) NOT NULL,
  `preferred_date` date NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 12. Newsletter subscribers
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `interest_area` varchar(100) NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 13. Staff leave requests
CREATE TABLE IF NOT EXISTS `staff_leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(255) NOT NULL,
  `staff_code` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `leave_type` varchar(100) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 14. Vendor partners
CREATE TABLE IF NOT EXISTS `vendor_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(255) NOT NULL,
  `business_type` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `services` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 15. Customer complaints
CREATE TABLE IF NOT EXISTS `customer_complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `complaint_text` text NOT NULL,
  `resolution_expectation` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
