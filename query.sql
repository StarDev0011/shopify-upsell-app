/***Asked questions
https://ecommerce.shopify.com/c/shopify-apis-and-technology/t/how-to-use-shopify-api-js-with-registered-script-via-script-tag-556488
https://community.shopify.com/c/Shopify-APIs-SDKs/Shopify-How-to-test-webhooks-in-php/m-p/497334#M32144
https://community.shopify.com/c/Shopify-APIs-SDKs/Is-API-available-for-getting-automatic-discount-code/m-p/503232#M32635
****/

/**
Cron
https://localhost/tops-upsell/index.php/cron/get_install_process
https://localhost/tops-upsell/index.php/cron/get_discount_codes
https://localhost/tops-upsell/index.php/cron/get_product_collections
https://localhost/tops-upsell/index.php/cron/add_shop_products
https://localhost/tops-upsell/index.php/cron/proccess_collection
https://localhost/tops-upsell/index.php/cron/proccess_variant_images
https://localhost/tops-upsell/index.php/cron/update_shop_status

https://topsdemo.co.in/qa/shopify/dev-tops-upsell/index.php/cron/get_discount_codes
https://topsdemo.co.in/qa/shopify/tops-upsell/index.php/cron/get_discount_codes
https://scarcifyapps.com/shopifyupsell/index.php/cron/get_discount_codes - Live(Client)
https://scarcifyapps.com/shopifyupsell/index.php/cron/get_product_collections


https://smartcartupsellbundle.com/index.php/cron/get_discount_codes
https://smartcartupsellbundle.com/index.php/cron/get_product_collections
https://smartcartupsellbundle.com/index.php/cron/get_install_process
https://smartcartupsellbundle.com/index.php/cron/add_shop_products
https://smartcartupsellbundle.com/index.php/cron/proccess_variant_images
https://smartcartupsellbundle.com/index.php/cron/change_expired_store_image_status


https://smartcartupsellbundle.com/index.php/cron_test/check_data
**/

SELECT pc.*,p.shop_id,p.product_id as pd FROM `product_collections` pc LEFT JOIN products p on pc.`product_id`=p.product_id WHERE shop_id=14235500601
SELECT pc.`product_id`,pc.`variant_id`,p.shop_id,p.product_id as pd FROM `product_variants` pc LEFT JOIN products p on pc.`product_id`=p.product_id WHERE shop_id=14235500601
DELETE pc
FROM
    `product_collections` AS pc
LEFT JOIN products p ON
    pc.`product_id` = p.product_id
WHERE
    shop_id = 14235500601
--------------------------------------

DELETE FROM `products` WHERE `shop_id`=14375810

------------------------------
DELETE pc FROM `product_variants` AS pc LEFT JOIN products p ON pc.`product_id` = p.product_id WHERE p.shop_id = 14235500601 
------------------------------

UPDATE `product_variants` pv JOIN products p On p.product_id=pv.product_id JOIN `shop` `s` ON `s`.`shop_id`=`p`.`shop_id` SET `is_image_processed`=1 WHERE s.myshopify_domain = "shop-andina.myshopify.com"
------------------------------
/** 12-7-2018 **/
ALTER TABLE `product_variants` ADD `sku` VARCHAR(50) NULL AFTER `price`;

/**17-7-2018**/
ALTER TABLE `bundles_master` CHANGE `id` `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `bundle_products` CHANGE `id` `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `bundles_master` CHANGE `bundle_label` `bundle_label` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `bundles_master` CHANGE `bundle_title` `bundle_title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `bundles_master` CHANGE `offer_description` `offer_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `bundles_master` CHANGE `check_stock` `check_stock` BIT(1) NOT NULL;
ALTER TABLE `bundles_master` CHANGE `use_target_products` `use_target_products` BIT(1) NOT NULL;
ALTER TABLE `bundles_master` CHANGE `use_product_quantity` `use_product_quantity` BIT(1) NOT NULL;
ALTER TABLE `bundles_master` CHANGE `inserted_date` `created_date` DATETIME NOT NULL;
ALTER TABLE `bundles_master` CHANGE `updated_date` `modified_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `bundles_master` CHANGE `status` `status` BIT(1) NOT NULL DEFAULT b'1';
ALTER TABLE `bundle_products` CHANGE `bundle_id` `bundle_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `bundle_products` ADD INDEX(`bundle_id`);
ALTER TABLE `bundle_products` CHANGE `product_id` `product_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `bundle_products` ADD INDEX(`product_id`);
ALTER TABLE `shop` CHANGE `shop_id` `shop_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `shop` CHANGE `dateTime` `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `billing_log` CHANGE `dateTime` `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `cart_log` CHANGE `dateTime` `created_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `orders` CHANGE `inserted_date` `created_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `order_details` CHANGE `inserted_date` `created_date` DATETIME NOT NULL;
ALTER TABLE `products` CHANGE `dateTime` `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `products` CHANGE `shop_id` `shop_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `products` CHANGE `product_id` `product_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `product_collections` CHANGE `product_id` `product_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `product_collections` CHANGE `collection_id` `collection_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `product_variants` CHANGE `product_id` `product_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `product_variants` CHANGE `variant_id` `variant_id` BIGINT(11) NOT NULL;
ALTER TABLE `bundle_products` CHANGE `product_id` `product_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `bundle_products` CHANGE `variant_id` `variant_id` BIGINT(11) NULL DEFAULT NULL;
ALTER TABLE `product_variants` CHANGE `price` `price` FLOAT NOT NULL;
ALTER TABLE `orders` CHANGE `shop_id` `shop_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `order_details` CHANGE `shop_id` `shop_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `order_details` CHANGE `product_id` `product_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `order_details` CHANGE `variant_id` `variant_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `orders` CHANGE `confirmed` `confirmed` BIT(1) NULL DEFAULT b'0';
ALTER TABLE `views_log` CHANGE `bundle_id` `bundle_id` BIGINT(11) UNSIGNED NOT NULL;
ALTER TABLE `views_log` CHANGE `dateTime` `created_date` DATE NOT NULL;
ALTER TABLE `shop` CHANGE `zip` `zip` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `shop` CHANGE `phone` `phone` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `shop` CHANGE `address` `address` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `bundles_master` CHANGE `min_qty` `min_qty` INT(5) NULL DEFAULT NULL;
ALTER TABLE `bundles_master` CHANGE `max_qty` `max_qty` INT(5) NULL DEFAULT NULL;
ALTER TABLE `product_variants` CHANGE `inventory` `inventory` INT(5) NOT NULL;
ALTER TABLE `views_log` ADD `type` BIT(1) NOT NULL DEFAULT b'1' COMMENT '1:Bundle,0:Product' AFTER `bundle_id`;
ALTER TABLE `bundles_master` CHANGE `min_price` `min_price` FLOAT(5,2) NULL DEFAULT NULL;
ALTER TABLE `bundles_master` CHANGE `max_price` `max_price` FLOAT(5,2) NULL DEFAULT NULL;
ALTER TABLE `bundle_products` CHANGE `type` `type` ENUM('p','t') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'p' COMMENT 't: Target product, P : Triggerd Product';


/** 28-7-2018 **/
ALTER TABLE `settings`
  DROP `hide_return_cart_link`;

ALTER TABLE `bundles_master` CHANGE `min_price` `min_price` FLOAT NULL DEFAULT NULL;
ALTER TABLE `bundles_master` CHANGE `max_price` `max_price` FLOAT NULL DEFAULT NULL; 

/** 6-8-2018 **/
ALTER TABLE `bundles_master` ADD `is_popup` BIT(1) NULL DEFAULT b'1' AFTER `use_product_quantity`;

ALTER TABLE `bundles_master` ADD `discount_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: standar, 1: discount_code, 2: free shipping, 3 buy one get one' AFTER `max_qty`;
ALTER TABLE `bundles_master` ADD `discount_id` INT(11) NULL AFTER `discount_type`, ADD `discount_goal` DECIMAL(7,2) NULL AFTER `discount_id`, ADD `discount_text` VARCHAR(500) NULL AFTER `discount_goal`, ADD INDEX (`discount_id`);
ALTER TABLE `bundles_master` CHANGE `discount_goal` `discount_goal_amount` DECIMAL(7,2) NULL DEFAULT NULL;
ALTER TABLE `bundles_master` ADD `offer_headline` VARCHAR(500) NULL AFTER `discount_id`;

ALTER TABLE `cart_log` ADD `shop_id` BIGINT(11) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `bundle_products` CHANGE `type` `type` ENUM('p','t') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'p' COMMENT 't: Triggered Product: P: Bundle product';
ALTER TABLE `views_log` ADD `shop_id` BIGINT(11) NOT NULL AFTER `id`;

/**19-12-18***/
ALTER TABLE `discount_details` ADD `entitled_type` TINYINT(1) NULL COMMENT '0:product, 1: collection' AFTER `entitled_product_ids`;
ALTER TABLE `discount_details` ADD `prerequisite_type` TINYINT(1) NULL COMMENT '0:product, 1: collection' AFTER `prerequisite_product_ids`;
ALTER TABLE `collections` ADD `modified_date` DATETIME NULL AFTER `rules`;
ALTER TABLE `collections` ADD `created_date` DATETIME NULL AFTER `rules`;

/*** 13-2-2019 ***/
ALTER TABLE `product_variants` ADD `image` TEXT NULL AFTER `sku`; 

/*** 4-4-2019 ***/
ALTER TABLE `shop` ADD `is_data_added` BIT(1) NOT NULL DEFAULT b'0' COMMENT '0: No, 1: Yes' AFTER `shop_id`;
/** 15-4-2019 **/
ALTER TABLE `shop` ADD `shop_status` VARCHAR(20) NULL AFTER `charge_status`; 

ALTER TABLE `discount_details` ADD `entitled_variant_ids` BIGINT(11) UNSIGNED NULL AFTER `entitled_product_ids`; 
ALTER TABLE `discount_details` ADD `prerequisite_variant_ids` BIGINT(11) UNSIGNED NULL AFTER `prerequisite_product_ids`; 
ALTER TABLE `shop` ADD `is_products_added` BIT(1) NOT NULL DEFAULT b'0' AFTER `is_data_added`;

ALTER TABLE `products` ADD `is_collection_processed` BIT(1) NOT NULL DEFAULT b'0' COMMENT '0:No,1:Yes' AFTER `product_id`; 

/** 10-9-2019 **/
ALTER TABLE `cross_sell_bundle` CHANGE `collection_id` `collection_id` BIGINT(11) NULL;
ALTER TABLE `cross_sell_bundle` CHANGE `collection_id` `collection_id` BIGINT(11) NULL DEFAULT NULL;

/**18-9-2019**/
ALTER TABLE `cross_sell_bundle` ADD `discount_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: standar, 1: discount_code, 2: free shipping, 3 buy one get one' AFTER `collection_id`, ADD `discount_id` BIGINT(11) UNSIGNED NOT NULL AFTER `discount_type`, ADD INDEX (`discount_id`);
ALTER TABLE `cross_sell_bundle` ADD `offer_headline` VARCHAR(500) NULL AFTER `discount_id`;



/** 4-10-2018 **/
ALTER TABLE `cross_sell_bundle` ADD `success_text` VARCHAR(500) NULL AFTER `offer_headline`;

/*** 21-1-2020 ***/
ALTER TABLE `contact_us` ADD `website_link` TEXT NULL AFTER `email`;
