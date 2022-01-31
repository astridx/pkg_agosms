CREATE TABLE IF NOT EXISTS `#__agosms_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',

  `url` varchar(255) NOT NULL DEFAULT '',
  `coordinates` varchar(255) NOT NULL DEFAULT '',
  `showdefaultpin` tinyint(1) NOT NULL DEFAULT 0,
  `customPinPath` varchar(255) NOT NULL DEFAULT '',
  `customPinSize` varchar(255) NOT NULL DEFAULT '',
  `customPinShadowPath` varchar(255) NOT NULL DEFAULT '',
  `customPinShadowSize` varchar(255) NOT NULL DEFAULT '',
  `customPinOffset` varchar(255) NOT NULL DEFAULT '',
  `customPinPopupOffset` varchar(255) NOT NULL DEFAULT '',
  `showpopup` tinyint(1) NOT NULL DEFAULT 0,
  `customiconstart_icon` varchar(255) NOT NULL DEFAULT '',
  `customiconstart_markercolor` varchar(255) NOT NULL DEFAULT '',
  `customiconstart_iconcolor` varchar(255) NOT NULL DEFAULT '',
  `customiconstart_extraclasses` varchar(255) NOT NULL DEFAULT '',
  `customiconstart_spin` varchar(255) NOT NULL DEFAULT '',

  `awesomeicon_icon` varchar(255) NOT NULL DEFAULT '',
  `awesomeicon_markercolor` varchar(255) NOT NULL DEFAULT '',
  `awesomeicon_iconcolor` varchar(255) NOT NULL DEFAULT '',
  `awesomeicon_extraclasses` varchar(255) NOT NULL DEFAULT '',
  `awesomeicon_spin` varchar(255) NOT NULL DEFAULT '',




  `popuptext` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__agosms_details` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD KEY `idx_access` (`access`);

ALTER TABLE `#__agosms_details` ADD COLUMN  `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN  `state` tinyint(3) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD KEY `idx_catid` (`catid`);

ALTER TABLE `#__agosms_details` ADD COLUMN  `published` tinyint(1) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN  `publish_up` datetime AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN  `publish_down` datetime AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD KEY `idx_state` (`published`);

ALTER TABLE `#__agosms_details` ADD COLUMN  `language` char(7) NOT NULL DEFAULT '*' AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD KEY `idx_language` (`language`);

ALTER TABLE `#__agosms_details` ADD COLUMN  `ordering` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN  `params` text NOT NULL AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN `checked_out` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN `checked_out_time` datetime AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD KEY `idx_checkout` (`checked_out`);

ALTER TABLE `#__agosms_details` ADD COLUMN  `featured` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'Set if agosm is featured.';

ALTER TABLE `#__agosms_details` ADD KEY `idx_featured_catid` (`featured`,`catid`);

ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm1` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm2` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm3` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm4` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm5` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm6` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm7` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm8` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm9` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm10` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm11` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm12` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm13` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm14` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm15` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm16` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm17` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm18` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm19` char(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN  `cusotm20` char(255) NOT NULL DEFAULT '' AFTER `alias`;

ALTER TABLE `#__agosms_details` ADD COLUMN `created` datetime NOT NULL AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `created_by` int unsigned NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `created_by_alias` varchar(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `modified` datetime NOT NULL AFTER `alias`;
ALTER TABLE `#__agosms_details` ADD COLUMN `modified_by` int unsigned NOT NULL DEFAULT 0 AFTER `alias`;
