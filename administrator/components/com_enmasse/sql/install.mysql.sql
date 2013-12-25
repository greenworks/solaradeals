SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET AUTOCOMMIT=0;
START TRANSACTION;

CREATE TABLE IF NOT EXISTS `#__enmasse_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `parent_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_coupon_element` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `font_size` int(2) NOT NULL,
  `width` int(2) NOT NULL,
  `height` int(2) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

INSERT INTO `#__enmasse_coupon_element` (`id`, `name`, `x`, `y`, `font_size`, `width`, `height`, `published`) VALUES
(1, 'dealName', 8, 140, 20, 600, 65, 1),
(2, 'serial', 380, 70, 12, 200, 50, 1),
(3, 'merchantName', 320, 250, 14, 280, 50, 1),
(4, 'highlight', 8, 325, 10, 280, 50, 1),
(5, 'personName', 8, 250, 14, 280, 50, 1),
(6, 'term', 320, 325, 10, 280, 50, 1);

CREATE TABLE IF NOT EXISTS `#__enmasse_deal` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `slug_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_desc` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `highlight` text COLLATE utf8_unicode_ci NOT NULL,
  `pic_dir` varchar(550) COLLATE utf8_unicode_ci NOT NULL,
  `terms` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET latin1,
  `origin_price` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `min_needed_qty` int(11) NOT NULL,
  `max_buy_qty` int(11) NOT NULL,
  `max_coupon_qty` int(11) NOT NULL DEFAULT '-1',
  `max_qty` int(11) NOT NULL,
  `cur_sold_qty` int(11) NOT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `merchant_id` bigint(20) DEFAULT NULL,
  `sales_person_id` bigint(20) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'On Sales',
  `published` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug_name` (`slug_name`),
  KEY `merchant_id_idx` (`merchant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_deal_category` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `deal_id` int(20) NOT NULL,
  `category_id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_deal_location` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `deal_id` int(20) NOT NULL,
  `location_id` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_delivery_gty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `#__enmasse_delivery_gty` (`id`, `name`, `class_name`, `created_at`, `updated_at`) VALUES
(1, 'Email', 'email', '2010-10-25 12:00:00', '2010-10-25 12:00:00');

CREATE TABLE IF NOT EXISTS `#__enmasse_email_template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `slug_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `avail_attribute` varchar(225) CHARACTER SET latin1 NOT NULL,
  `subject` varchar(225) CHARACTER SET latin1 DEFAULT NULL,
  `content` text CHARACTER SET latin1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_name` (`slug_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

INSERT INTO `#__enmasse_email_template` (`id`, `slug_name`, `avail_attribute`, `subject`, `content`, `created_at`, `updated_at`) VALUES
(1, 'receipt', '$buyerName, $buyerEmail, $deliveryName, $deliveryEmail, $orderId, $dealName, $price, $createdAt', 'You have made an Order', '<p>Hi $buyerName,</p>\r\n<p>You have made an Order at EnMasse with following detail:</p>\r\n<table border="0">\r\n<tr><td><b>Order:</b><td><td>$orderId</td></tr>\r\n<tr><td><b>Deal:</b><td><td>$dealName</td></tr>\r\n<tr><td><b>Total Qty:</b><td><td>$totalQty</td></tr>\r\n<tr><td><b>Total Price:</b><td><td>$totalPrice</td></tr>\r\n<tr><td><b>Purchase Date:</b><td><td>$createdAt</td></tr>\r\n<tr><td><b>Delivery:</b><td><td>$deliveryName ($deliveryEmail)</td></tr>\r\n</table>', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'confirm_deal_buyer', '$orderId, $dealName, $buyerName, $deliveryName, $deliveryEmail', 'Deal $dealName has been confirmed.', '<p>Hi $buyerName,</p>\r\n<p>Your deal $dealName you ordered has been confirmed.</p>\r\n<p>The coupon will be delivered to $deliveryName ($deliveryEmail)</p>\r\nOrder Id: $orderId', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'confirm_deal_receiver', '$orderId, $dealName, $buyerName, $deliveryName, $deliveryMsg, $linkToCoupon', 'Receive your coupon !!!', '<p>Hi $deliveryName,</p>\r\n<p>\r\n$buyerName has bought you a set of coupon for <a href="$linkToCoupon" target="_blank">$dealName</a></p>\r\n<p>$deliveryMsg</p>\r\n<br/>\r\n<font size=''1''>Please go to <a href="$linkToCoupon" target="_blank">$linkToCoupon</a> if the hyperlink has being blocked.</font>\r\n', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'void_deal', '$buyerName, $orderId, $dealName, $refundAmt', 'Deal $dealName has been canceled', '<p>Hi $buyerName,</p>\r\n<p>The Order($orderId) for deal $dealName has been cancel.</p>\r\n<p>$refundAmt will be refunded to you.</p>', '0000-00-00 00:00:00', '0000-00-00 00:00:00');


CREATE TABLE IF NOT EXISTS `#__enmasse_invty` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) NOT NULL,
  `pdt_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deallocated_at` datetime NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_location` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `parent_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_merchant` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sales_person_id` bigint(20) DEFAULT NULL,
  `web_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `logo_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  `google_map_lat` float NOT NULL,
  `google_map_long` float NOT NULL,
  `google_map_width` float NOT NULL,
  `google_map_height` float NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__enmasse_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_buyer_paid` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buyer_id` bigint(20) DEFAULT NULL,
  `buyer_detail` longtext COLLATE utf8_unicode_ci,
  `pay_gty_id` bigint(20) DEFAULT NULL,
  `pay_detail` longtext COLLATE utf8_unicode_ci,
  `delivery_gty_id` bigint(20) DEFAULT NULL,
  `delivery_detail` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `buyer_id_idx` (`buyer_id`),
  KEY `pay_gty_id_idx` (`pay_gty_id`),
  KEY `delivery_gty_id_idx` (`delivery_gty_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_order_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `pdt_id` bigint(20) DEFAULT NULL,
  `pdt_promo_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pdt_id_idx` (`pdt_id`),
  KEY `pdt_promo_id_idx` (`pdt_promo_id`),
  KEY `order_id_idx` (`order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_pay_gty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `attributes` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attribute_config` longtext COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

INSERT INTO `#__enmasse_pay_gty` (`id`, `name`, `class_name`, `attributes`, `attribute_config`, `published`, `created_at`, `updated_at`) VALUES
(1, 'Credit Card / Debit Card / Paypal', 'paypal', 'merchant_email,api_username,signature,country_code,currency_code', '{"merchant_email":"","api_username":"","signature":"","country_code":"","currency_code":""}', 1, '0000-00-00 00:00:00', '2011-02-17 02:30:40'),
(2, 'Cash / Bank Transfer', 'cash', 'instruction', '{"instruction":""}', 1, '0000-00-00 00:00:00', '2011-03-24 06:42:36');

CREATE TABLE IF NOT EXISTS `#__enmasse_sales_person` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__enmasse_setting` (
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `address1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tax_number1` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tax_number2` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `logo_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `contact_fax` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `customer_support_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `currency_prefix` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `currency_postfix` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `currency_decimal` tinyint(2) NOT NULL,
  `currency_separator` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `currency_decimal_separator` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `image_height` int(5) NOT NULL,
  `image_width` int(5) NOT NULL,
  `article_id` int(5) NOT NULL,
  `active_popup_location` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `coupon_bg_url` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `minute_release_invty` int(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `logo_url` (`logo_url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `#__enmasse_setting` (`company_name`, `id`, `address1`, `address2`, `city`, `state`, `country`, `postal_code`, `tax`, `tax_number1`, `tax_number2`, `logo_url`, `contact_number`, `contact_fax`, `customer_support_email`, `default_currency`, `currency_prefix`, `currency_postfix`, `currency_decimal`, `currency_separator`, `currency_decimal_separator`, `image_height`, `image_width`, `article_id`, `active_popup_location`, `theme`, `coupon_bg_url`, `minute_release_invty`, `created_at`, `updated_at`) VALUES
('', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', 'USD', '$', '', 2, ',', '', 252, 400, 0, 0, 'apollo_red', '', 10, '0000-00-00 00:00:00', '2011-04-05 12:05:30');

CREATE TABLE IF NOT EXISTS `#__enmasse_tax` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `tax_rate` double NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



COMMIT;
