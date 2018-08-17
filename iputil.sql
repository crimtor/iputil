DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `hashed_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_username` (`username`)
);

INSERT INTO `admins` VALUES (1,'Steve','Public','steve@user.com','user1234','$2y$10$bq65HtBkj7W8DDhA.ANB7.OaxN0/dXV4RMlk6CfBBEkC//U8.bRCm');

DROP TABLE IF EXISTS `ip_table_for_`;
CREATE TABLE `ip_table_for_` (
  `ip_address` varchar(16) DEFAULT NULL,
  `mac_address` varchar(55) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL,
  `online` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_location_id` (`location_id`)
);

INSERT INTO `ip_table_for_` VALUES (1, '192.168.1.101', 'aa:bb:cc:dd:ee', 'Fax Machine', 1, 1);

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(255) DEFAULT NULL,
  `state` varchar(15) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`location_name`)
);

INSERT INTO `locations` VALUES (1,'Cool Site', 'Arizona', 'Tempe');

ALTER TABLE `mysite`
ADD CONSTRAINT `fk_location_id`
FOREIGN KEY ('location_id') REFERENCES `locations`('id');
