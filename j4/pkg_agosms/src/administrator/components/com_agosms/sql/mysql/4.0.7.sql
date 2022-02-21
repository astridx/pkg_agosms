ALTER TABLE `#__agosms_details` ADD COLUMN `created` datetime NOT NULL AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `created_by` int unsigned NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `created_by_alias` varchar(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `modified` datetime NOT NULL AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `modified_by` int unsigned NOT NULL DEFAULT 0 AFTER `alias`;

