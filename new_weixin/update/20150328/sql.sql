ALTER TABLE `zfwx_wxuser_site_indexcontent` 
ADD COLUMN `content_menu3_title` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu2_item6_link`,
ADD COLUMN `content_menu3_item1_text` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu3_title`,
ADD COLUMN `content_menu3_item1_link` VARCHAR(255) NULL DEFAULT NULL AFTER `content_menu3_item1_text`,
ADD COLUMN `content_menu3_item2_text` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu3_item1_link`,
ADD COLUMN `content_menu3_item2_link` VARCHAR(255) NULL DEFAULT NULL AFTER `content_menu3_item2_text`,
ADD COLUMN `content_menu3_item3_text` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu3_item2_link`,
ADD COLUMN `content_menu3_item3_link` VARCHAR(255) NULL DEFAULT NULL AFTER `content_menu3_item3_text`,
ADD COLUMN `content_menu3_item4_text` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu3_item3_link`,
ADD COLUMN `content_menu3_item4_link` VARCHAR(255) NULL DEFAULT NULL AFTER `content_menu3_item4_text`,
ADD COLUMN `content_menu3_item5_text` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu3_item4_link`,
ADD COLUMN `content_menu3_item5_link` VARCHAR(255) NULL DEFAULT NULL AFTER `content_menu3_item5_text`,
ADD COLUMN `content_menu3_item6_text` VARCHAR(45) NULL DEFAULT NULL AFTER `content_menu3_item5_link`,
ADD COLUMN `content_menu3_item6_link` VARCHAR(255) NULL DEFAULT NULL AFTER `content_menu3_item6_text`,
ADD COLUMN `content_goodslist4_ids` VARCHAR(255) NULL DEFAULT NULL AFTER `content_goodslist3_ids`,
ADD COLUMN `content_banner_1` TEXT NULL AFTER `content_goodslist4_ids`,
ADD COLUMN `content_banner_2` TEXT NULL AFTER `content_banner_1`,
ADD COLUMN `content_banner_3` TEXT NULL AFTER `content_banner_2`;
