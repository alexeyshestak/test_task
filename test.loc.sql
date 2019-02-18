
CREATE DATABASE IF NOT EXISTS `test.loc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE `test.loc`;

CREATE TABLE `merchant` (
  `mid` decimal(18,0) NOT NULL,
  `dba` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `batch` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `batch_date` date NOT NULL,
  `batch_ref_num` decimal(24,0) NOT NULL,
  PRIMARY KEY (`bid`),
  UNIQUE KEY `batch_index` (`batch_date`,`batch_ref_num`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `card_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `transaction_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `merchant_id` decimal(18,0) NOT NULL,
  `batch_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `type_id` int(10) NOT NULL,
  `card_type_id` int(10) NOT NULL,
  `card_num` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `merchant_id` (`merchant_id`),
  KEY `batch_id` (`batch_id`),
  KEY `date` (`date`),
  KEY `type_id` (`type_id`),
  KEY `card_type_id` (`card_type_id`),
  CONSTRAINT `batch` FOREIGN KEY (`batch_id`) REFERENCES `batch` (`bid`),
  CONSTRAINT `card_type` FOREIGN KEY (`card_type_id`) REFERENCES `card_type` (`id`),
  CONSTRAINT `merchant` FOREIGN KEY (`merchant_id`) REFERENCES `merchant` (`mid`),
  CONSTRAINT `trans_type` FOREIGN KEY (`type_id`) REFERENCES `transaction_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
