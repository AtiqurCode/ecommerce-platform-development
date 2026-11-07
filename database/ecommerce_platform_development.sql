-- ecommerce_platform_development demo dump
-- Generated 2026-11-07T17:42:31+00:00

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`,`name`,`email`,`email_verified_at`,`password`,`two_factor_secret`,`two_factor_recovery_codes`,`two_factor_confirmed_at`,`remember_token`,`created_at`,`updated_at`) VALUES ('1','Admin User','admin@example.com','2026-11-07 17:42:04','$2y$12$VyMOEhn0k6wSWs4n0/E62ezLgqZEjtlvdSVn7169m9jNfeoula07G',NULL,NULL,NULL,NULL,'2026-11-07 17:42:04','2026-11-07 17:42:04');
INSERT INTO `users` (`id`,`name`,`email`,`email_verified_at`,`password`,`two_factor_secret`,`two_factor_recovery_codes`,`two_factor_confirmed_at`,`remember_token`,`created_at`,`updated_at`) VALUES ('2','Test User','test@example.com','2026-11-07 17:42:05','$2y$12$G1mCjru30ZyQPW6FLiEUkOe8dGFkZ.cLn/wDcCtWcPP5kmPFR1Wt2',NULL,NULL,NULL,NULL,'2026-11-07 17:42:05','2026-11-07 17:42:05');

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('1','Electronics','electronics','Phones, laptops, and everyday gadgets.','2026-11-07 17:42:05','2026-11-07 17:42:05');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('2','Fashion','fashion','Clothing and accessories for men and women.','2026-11-07 17:42:05','2026-11-07 17:42:05');
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('3','Home & Living','home-living','Furniture and essentials for modern homes.','2026-11-07 17:42:05','2026-11-07 17:42:05');

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE `subcategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcategories_category_id_slug_unique` (`category_id`,`slug`),
  CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subcategories` (`id`,`category_id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('1','1','Mobile Phones','mobile-phones','Smartphones and mobile accessories.','2026-11-07 17:42:05','2026-11-07 17:42:05');
INSERT INTO `subcategories` (`id`,`category_id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('2','1','Laptops','laptops','Notebooks for work and creative tasks.','2026-11-07 17:42:05','2026-11-07 17:42:05');
INSERT INTO `subcategories` (`id`,`category_id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('3','2','Men\'s Clothing','mens-clothing','Casual and formal wear for men.','2026-11-07 17:42:05','2026-11-07 17:42:05');
INSERT INTO `subcategories` (`id`,`category_id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('4','2','Women\'s Clothing','womens-clothing','Everyday fashion for women.','2026-11-07 17:42:05','2026-11-07 17:42:05');
INSERT INTO `subcategories` (`id`,`category_id`,`name`,`slug`,`description`,`created_at`,`updated_at`) VALUES ('5','3','Furniture','furniture','Desks, chairs, and living room pieces.','2026-11-07 17:42:05','2026-11-07 17:42:05');

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subcategory_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_price` decimal(12,2) NOT NULL,
  `new_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_subcategory_id_index` (`subcategory_id`),
  CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('1','1','iPhone 17 Pro','iphone-17-pro','Flagship smartphone with a bright display, advanced camera system, and all-day battery life.','products/iphone-17-pro.jpg','1499.00','1299.00','2026-11-07 17:42:13','2026-11-07 17:42:13');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('2','1','Samsung Galaxy S26','samsung-galaxy-s26','Premium Android phone with a sharp AMOLED display and a versatile multi-lens camera.','products/samsung-galaxy-s26.jpg','1399.00','1199.00','2026-11-07 17:42:14','2026-11-07 17:42:14');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('3','1','Google Pixel 10','google-pixel-10','Clean Android experience with excellent computational photography and timely software updates.','products/google-pixel-10.jpg','999.00','899.00','2026-11-07 17:42:15','2026-11-07 17:42:15');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('4','2','MacBook Pro 14','macbook-pro-14','Powerful laptop designed for developers and creators who need reliable performance on the go.','products/macbook-pro-14.jpg','2499.00','2299.00','2026-11-07 17:42:16','2026-11-07 17:42:16');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('5','2','Dell XPS 15','dell-xps-15','Slim Windows laptop with a vivid display and strong battery life for professional work.','products/dell-xps-15.jpg','1899.00','1699.00','2026-11-07 17:42:17','2026-11-07 17:42:17');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('6','2','Lenovo ThinkPad X1','lenovo-thinkpad-x1','Business notebook with a durable build, comfortable keyboard, and enterprise-ready security.','products/lenovo-thinkpad-x1.jpg','1799.00','1599.00','2026-11-07 17:42:19','2026-11-07 17:42:19');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('7','3','Men\'s Oxford Shirt','mens-oxford-shirt','Breathable cotton shirt with a clean fit for office days and weekend wear.','products/mens-oxford-shirt.jpg','59.00','39.00','2026-11-07 17:42:20','2026-11-07 17:42:20');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('8','3','Men\'s Chino Pants','mens-chino-pants','Versatile chinos with a soft stretch fabric for everyday comfort.','products/mens-chino-pants.jpg','79.00','59.00','2026-11-07 17:42:21','2026-11-07 17:42:21');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('9','3','Cotton Crew T-Shirt','cotton-crew-t-shirt','Soft everyday tee with a classic fit and durable stitched seams.','products/cotton-crew-t-shirt.jpg','29.00','19.00','2026-11-07 17:42:22','2026-11-07 17:42:22');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('10','4','Women\'s Linen Blouse','womens-linen-blouse','Lightweight linen blouse with a relaxed silhouette for warm-weather outfits.','products/womens-linen-blouse.jpg','69.00','49.00','2026-11-07 17:42:24','2026-11-07 17:42:24');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('11','4','Women\'s Midi Dress','womens-midi-dress','Flattering midi dress made from soft fabric for work events and casual evenings.','products/womens-midi-dress.jpg','89.00','69.00','2026-11-07 17:42:25','2026-11-07 17:42:25');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('12','4','Wool Blend Overcoat','wool-blend-overcoat','Warm overcoat with a tailored cut for colder city weather.','products/wool-blend-overcoat.jpg','220.00','179.00','2026-11-07 17:42:26','2026-11-07 17:42:26');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('13','5','Ergonomic Office Chair','ergonomic-office-chair','Adjustable office chair with lumbar support for long work sessions.','products/ergonomic-office-chair.jpg','299.00','249.00','2026-11-07 17:42:27','2026-11-07 17:42:27');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('14','5','Walnut Standing Desk','walnut-standing-desk','Height-adjustable desk with a warm wood finish for focused home offices.','products/walnut-standing-desk.jpg','699.00','599.00','2026-11-07 17:42:29','2026-11-07 17:42:29');
INSERT INTO `products` (`id`,`subcategory_id`,`name`,`slug`,`description`,`image`,`old_price`,`new_price`,`created_at`,`updated_at`) VALUES ('15','5','Minimalist Bookshelf','minimalist-bookshelf','Open wooden bookshelf with clean lines for living rooms and studies.','products/minimalist-bookshelf.jpg','249.00','199.00','2026-11-07 17:42:30','2026-11-07 17:42:30');

SET FOREIGN_KEY_CHECKS=1;
