DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_agosms.agosm', 'com_agosms.category');

DROP TABLE IF EXISTS `#__agosms`;
