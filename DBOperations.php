<?php

include("./lib/settings.php");
global $ERROR_MSG;
$db->rawQuery("
CREATE TABLE IF NOT EXISTS `msc_discipline` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `discipline_name` varchar(256) NOT NULL,
  `discipline_details` text,
  `discipline_pubflg` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Job role details' AUTO_INCREMENT=12");
echo $db;

?>