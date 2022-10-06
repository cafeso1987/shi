-- MySQL dump 10.13  Distrib 8.0.30, for Linux (x86_64)
--
-- Host: localhost    Database: tradexpro_demo
-- ------------------------------------------------------
-- Server version	8.0.30-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `tradexpro_demo`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `tradexpro_demo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `tradexpro_demo`;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_give_coin_histories`
--

DROP TABLE IF EXISTS `admin_give_coin_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_give_coin_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_give_coin_histories`
--

LOCK TABLES `admin_give_coin_histories` WRITE;
/*!40000 ALTER TABLE `admin_give_coin_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_give_coin_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_settings`
--

DROP TABLE IF EXISTS `admin_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_settings`
--

LOCK TABLES `admin_settings` WRITE;
/*!40000 ALTER TABLE `admin_settings` DISABLE KEYS */;
INSERT INTO `admin_settings` VALUES (1,'exchange_url','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(2,'coin_price','2.50','2022-08-16 07:46:21','2022-08-16 07:46:21'),(3,'coin_name','TradexPro','2022-08-16 07:46:21','2022-08-16 07:46:21'),(4,'app_title','TradexPro Admin','2022-08-16 07:46:21','2022-08-16 07:46:21'),(5,'maximum_withdrawal_daily','3','2022-08-16 07:46:21','2022-08-16 07:46:21'),(6,'mail_from','noreply@cpoket.com','2022-08-16 07:46:21','2022-08-16 07:46:21'),(7,'admin_coin_address','address','2022-08-16 07:46:21','2022-08-16 07:46:21'),(8,'base_coin_type','BTC','2022-08-16 07:46:21','2022-08-16 07:46:21'),(9,'minimum_withdrawal_amount','0.005','2022-08-16 07:46:21','2022-08-16 07:46:21'),(10,'maximum_withdrawal_amount','12','2022-08-16 07:46:21','2022-08-16 07:46:21'),(11,'maintenance_mode','no','2022-08-16 07:46:21','2022-08-16 07:46:21'),(12,'logo','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(13,'login_logo','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(14,'landing_logo','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(15,'favicon','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(16,'copyright_text','Copyright@2020','2022-08-16 07:46:21','2022-08-16 07:46:21'),(17,'pagination_count','10','2022-08-16 07:46:21','2022-08-16 07:46:21'),(18,'point_rate','1','2022-08-16 07:46:21','2022-08-16 07:46:21'),(19,'currency','USD','2022-08-16 07:46:21','2022-08-16 07:46:21'),(20,'lang','en','2022-08-16 07:46:21','2022-08-16 07:46:21'),(21,'company_name','Test Company','2022-08-16 07:46:21','2022-08-16 07:46:21'),(22,'primary_email','test@email.com','2022-08-16 07:46:21','2022-08-16 07:46:21'),(23,'sms_getway_name','twillo','2022-08-16 07:46:21','2022-08-16 07:46:21'),(24,'twillo_secret_key','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(25,'twillo_auth_token','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(26,'twillo_number','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(27,'ssl_verify','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(28,'mail_driver','SMTP','2022-08-16 07:46:21','2022-08-16 07:46:21'),(29,'mail_host','smtp.mailtrap.io','2022-08-16 07:46:21','2022-08-16 07:46:21'),(30,'mail_port','2525','2022-08-16 07:46:21','2022-08-16 07:46:21'),(31,'mail_username','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(32,'mail_password','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(33,'mail_encryption','null','2022-08-16 07:46:21','2022-08-16 07:46:21'),(34,'mail_from_address','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(35,'braintree_client_token','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(36,'braintree_environment','sandbox','2022-08-16 07:46:21','2022-08-16 07:46:21'),(37,'braintree_merchant_id','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(38,'braintree_public_key','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(39,'braintree_private_key','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(40,'clickatell_api_key','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(41,'number_of_confirmation','6','2022-08-16 07:46:21','2022-08-16 07:46:21'),(42,'referral_commission_percentage','10','2022-08-16 07:46:21','2022-08-16 07:46:21'),(43,'referral_signup_reward','10','2022-08-16 07:46:21','2022-08-16 07:46:21'),(44,'max_affiliation_level','3','2022-08-16 07:46:21','2022-08-16 07:46:21'),(45,'coin_api_user','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(46,'coin_api_pass','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(47,'coin_api_host','test5','2022-08-16 07:46:21','2022-08-16 07:46:21'),(48,'coin_api_port','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(49,'COIN_PAYMENT_PUBLIC_KEY','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(50,'COIN_PAYMENT_PRIVATE_KEY','test','2022-08-16 07:46:21','2022-08-16 07:46:21'),(51,'COIN_PAYMENT_CURRENCY','BTC','2022-08-16 07:46:21','2022-08-16 07:46:21'),(52,'ipn_merchant_id','','2022-08-16 07:46:21','2022-08-16 07:46:21'),(53,'ipn_secret','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(54,'payment_method_coin_payment','1','2022-08-16 07:46:22','2022-08-16 07:46:22'),(55,'payment_method_bank_deposit','1','2022-08-16 07:46:22','2022-08-16 07:46:22'),(56,'chain_link','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(57,'chain_id','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(58,'contract_address','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(59,'wallet_address','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(60,'private_key','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(61,'contract_decimal','18','2022-08-16 07:46:22','2022-08-16 07:46:22'),(62,'gas_limit','216200','2022-08-16 07:46:22','2022-08-16 07:46:22'),(63,'contract_coin_name','ETH','2022-08-16 07:46:22','2022-08-16 07:46:22'),(64,'kyc_enable_for_withdrawal','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(65,'kyc_nid_enable_for_withdrawal','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(66,'kyc_passport_enable_for_withdrawal','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(67,'kyc_driving_enable_for_withdrawal','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(68,'trade_limit_1','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(69,'maker_1','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(70,'taker_1','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(71,'NOCAPTCHA_SECRET','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(72,'NOCAPTCHA_SITEKEY','test','2022-08-16 07:46:22','2022-08-16 07:46:22'),(73,'google_recapcha','0','2022-08-16 07:46:22','2022-08-16 07:46:22'),(74,'landing_title','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(75,'landing_description','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(76,'footer_description','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(77,'landing_page_logo','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(78,'landing_feature_title','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(79,'market_trend_title','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(80,'trade_anywhere_title','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(81,'trade_anywhere_left_img','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(82,'secure_trade_title','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(83,'secure_trade_left_img','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(84,'customization_title','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(85,'customization_details','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(86,'apple_store_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(87,'android_store_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(88,'google_store_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(89,'macos_store_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(90,'windows_store_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(91,'linux_store_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(92,'api_link','','2022-08-16 07:46:22','2022-08-16 07:46:22'),(93,'trading_price_tolerance','10','2022-08-16 07:46:22','2022-08-16 07:46:22');
/*!40000 ALTER TABLE `admin_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliation_codes`
--

DROP TABLE IF EXISTS `affiliation_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliation_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `affiliation_codes_code_unique` (`code`),
  KEY `affiliation_codes_user_id_foreign` (`user_id`),
  CONSTRAINT `affiliation_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliation_codes`
--

LOCK TABLES `affiliation_codes` WRITE;
/*!40000 ALTER TABLE `affiliation_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliation_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliation_histories`
--

DROP TABLE IF EXISTS `affiliation_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliation_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `child_id` bigint unsigned NOT NULL,
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `system_fees` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `transaction_id` bigint DEFAULT NULL,
  `level` int NOT NULL,
  `order_type` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_id` bigint DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliation_histories_user_id_foreign` (`user_id`),
  KEY `affiliation_histories_child_id_foreign` (`child_id`),
  CONSTRAINT `affiliation_histories_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `affiliation_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliation_histories`
--

LOCK TABLES `affiliation_histories` WRITE;
/*!40000 ALTER TABLE `affiliation_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliation_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buy_coin_histories`
--

DROP TABLE IF EXISTS `buy_coin_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buy_coin_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL,
  `user_id` bigint NOT NULL,
  `coin` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `btc` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `doller` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `admin_confirmation` tinyint NOT NULL DEFAULT '0',
  `confirmations` int NOT NULL DEFAULT '0',
  `bank_sleep` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` int DEFAULT NULL,
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phase_id` bigint DEFAULT NULL,
  `referral_level` int DEFAULT NULL,
  `fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `bonus` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `referral_bonus` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `requested_amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `stripe_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy_coin_histories`
--

LOCK TABLES `buy_coin_histories` WRITE;
/*!40000 ALTER TABLE `buy_coin_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `buy_coin_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buy_coin_referral_histories`
--

DROP TABLE IF EXISTS `buy_coin_referral_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buy_coin_referral_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `buy_id` bigint NOT NULL,
  `phase_id` bigint NOT NULL,
  `child_id` bigint NOT NULL,
  `level` int NOT NULL,
  `system_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `amount` decimal(13,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy_coin_referral_histories`
--

LOCK TABLES `buy_coin_referral_histories` WRITE;
/*!40000 ALTER TABLE `buy_coin_referral_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `buy_coin_referral_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buys`
--

DROP TABLE IF EXISTS `buys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `condition_buy_id` bigint unsigned DEFAULT NULL,
  `trade_coin_id` int NOT NULL,
  `base_coin_id` int NOT NULL,
  `amount` decimal(19,8) unsigned NOT NULL,
  `price` decimal(19,8) unsigned NOT NULL,
  `processed` decimal(19,8) unsigned NOT NULL DEFAULT '0.00000000',
  `virtual_amount` decimal(19,8) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false = pending, true = success',
  `btc_rate` decimal(19,8) NOT NULL,
  `is_market` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = normal, 2 = active',
  `is_conditioned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = simple buy & 1 = condition buy',
  `category` tinyint NOT NULL DEFAULT '1' COMMENT '1 = exchange',
  `maker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `taker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `request_amount` decimal(19,8) unsigned NOT NULL DEFAULT '0.00000000',
  `processed_request_amount` decimal(19,8) unsigned NOT NULL DEFAULT '0.00000000',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buys`
--

LOCK TABLES `buys` WRITE;
/*!40000 ALTER TABLE `buys` DISABLE KEYS */;
/*!40000 ALTER TABLE `buys` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `buy_process_not_big_than_amount` BEFORE UPDATE ON `buys` FOR EACH ROW BEGIN
                                declare msg varchar(128);
                                if new.processed > OLD.amount then
                                  set msg = concat('Process Not Bigger than Amount');
                                  signal sqlstate '45000' set message_text = msg;
                                end if;
                              END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `co_wallet_withdraw_approvals`
--

DROP TABLE IF EXISTS `co_wallet_withdraw_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `co_wallet_withdraw_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `temp_withdraw_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `co_wallet_withdraw_approvals`
--

LOCK TABLES `co_wallet_withdraw_approvals` WRITE;
/*!40000 ALTER TABLE `co_wallet_withdraw_approvals` DISABLE KEYS */;
/*!40000 ALTER TABLE `co_wallet_withdraw_approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coin_pairs`
--

DROP TABLE IF EXISTS `coin_pairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coin_pairs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_coin_id` int NOT NULL,
  `child_coin_id` int NOT NULL,
  `value` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `change` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `high` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `low` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `initial_price` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `price` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coin_pairs_parent_coin_id_child_coin_id_unique` (`parent_coin_id`,`child_coin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coin_pairs`
--

LOCK TABLES `coin_pairs` WRITE;
/*!40000 ALTER TABLE `coin_pairs` DISABLE KEYS */;
/*!40000 ALTER TABLE `coin_pairs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coin_requests`
--

DROP TABLE IF EXISTS `coin_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coin_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(13,8) NOT NULL DEFAULT '0.00000000',
  `sender_user_id` bigint NOT NULL,
  `receiver_user_id` bigint NOT NULL,
  `sender_wallet_id` bigint NOT NULL,
  `receiver_wallet_id` bigint NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coin_requests`
--

LOCK TABLES `coin_requests` WRITE;
/*!40000 ALTER TABLE `coin_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `coin_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coins`
--

DROP TABLE IF EXISTS `coins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coin_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `network` tinyint NOT NULL DEFAULT '1',
  `is_withdrawal` tinyint NOT NULL DEFAULT '1',
  `is_deposit` tinyint NOT NULL DEFAULT '1',
  `is_buy` tinyint NOT NULL DEFAULT '1',
  `is_sell` tinyint NOT NULL DEFAULT '1',
  `coin_icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_base` tinyint(1) NOT NULL DEFAULT '1',
  `is_currency` tinyint(1) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) DEFAULT NULL,
  `is_wallet` tinyint(1) NOT NULL DEFAULT '0',
  `is_transferable` tinyint(1) NOT NULL DEFAULT '0',
  `is_virtual_amount` tinyint(1) NOT NULL DEFAULT '0',
  `trade_status` tinyint NOT NULL DEFAULT '1',
  `sign` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `minimum_buy_amount` decimal(19,8) NOT NULL DEFAULT '0.00000010',
  `maximum_buy_amount` decimal(19,8) NOT NULL DEFAULT '999999.00000000',
  `minimum_sell_amount` decimal(19,8) NOT NULL DEFAULT '0.00000010',
  `maximum_sell_amount` decimal(19,8) NOT NULL DEFAULT '999999.00000000',
  `minimum_withdrawal` decimal(19,8) NOT NULL DEFAULT '0.00000010',
  `maximum_withdrawal` decimal(19,8) NOT NULL DEFAULT '99999999.00000000',
  `max_send_limit` decimal(19,8) NOT NULL DEFAULT '99999999.00000000',
  `withdrawal_fees` decimal(29,18) NOT NULL DEFAULT '0.000000100000000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coins_coin_type_unique` (`coin_type`),
  UNIQUE KEY `coins_is_primary_unique` (`is_primary`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coins`
--

LOCK TABLES `coins` WRITE;
/*!40000 ALTER TABLE `coins` DISABLE KEYS */;
INSERT INTO `coins` VALUES (1,'Bitcoin','BTC',1,1,1,1,1,1,NULL,1,0,NULL,0,0,0,1,NULL,0.00000010,999999.00000000,0.00000010,999999.00000000,0.00000010,99999999.00000000,99999999.00000000,0.000000100000000000,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(2,'Tether USD','USDT',1,1,1,1,1,1,NULL,1,0,NULL,0,0,0,1,NULL,0.00000010,999999.00000000,0.00000010,999999.00000000,0.00000010,99999999.00000000,99999999.00000000,0.000000100000000000,'2022-08-16 07:46:22','2022-08-16 07:46:22');
/*!40000 ALTER TABLE `coins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condition_buys`
--

DROP TABLE IF EXISTS `condition_buys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condition_buys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `trade_coin_id` int NOT NULL,
  `base_coin_id` int NOT NULL,
  `amount` decimal(19,8) NOT NULL,
  `price` decimal(19,8) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false = pending, true = success',
  `btc_rate` decimal(19,8) NOT NULL,
  `category` tinyint NOT NULL DEFAULT '1' COMMENT '1 = exchange',
  `maker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `taker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condition_buys`
--

LOCK TABLES `condition_buys` WRITE;
/*!40000 ALTER TABLE `condition_buys` DISABLE KEYS */;
/*!40000 ALTER TABLE `condition_buys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condition_sells`
--

DROP TABLE IF EXISTS `condition_sells`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condition_sells` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `condition_buy_id` bigint unsigned NOT NULL,
  `trade_coin_id` int NOT NULL,
  `base_coin_id` int NOT NULL,
  `amount` decimal(19,8) NOT NULL,
  `price` decimal(19,8) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false = pending, true = success',
  `btc_rate` decimal(19,8) NOT NULL,
  `category` tinyint NOT NULL DEFAULT '1' COMMENT '1 = exchange',
  `maker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `taker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condition_sells`
--

LOCK TABLES `condition_sells` WRITE;
/*!40000 ALTER TABLE `condition_sells` DISABLE KEYS */;
/*!40000 ALTER TABLE `condition_sells` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_us`
--

DROP TABLE IF EXISTS `contact_us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_us` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_us`
--

LOCK TABLES `contact_us` WRITE;
/*!40000 ALTER TABLE `contact_us` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_us` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_pages`
--

DROP TABLE IF EXISTS `custom_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1',
  `data_order` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_pages`
--

LOCK TABLES `custom_pages` WRITE;
/*!40000 ALTER TABLE `custom_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deposite_transactions`
--

DROP TABLE IF EXISTS `deposite_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deposite_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `sender_wallet_id` bigint DEFAULT NULL,
  `receiver_wallet_id` bigint unsigned NOT NULL,
  `address_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `btc` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `doller` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `confirmations` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deposite_transactions`
--

LOCK TABLES `deposite_transactions` WRITE;
/*!40000 ALTER TABLE `deposite_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `deposite_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `author` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'What is Tradexpro exchange ?','Aenean condimentum nibh vel enim sodales scelerisque. Mauris quisn pellentesque odio, in vulputate turpis. Integer condimentum eni lorem pellentesque euismod. Nam rutrum accumsan nisl vulputate.',1,1,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(2,'How it works ?','Aenean condimentum nibh vel enim sodales scelerisque. Mauris quisn pellentesque odio, in vulputate turpis. Integer condimentum eni lorem pellentesque euismod. Nam rutrum accumsan nisl vulputate.',1,1,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(3,'What is the workflow ?','Aenean condimentum nibh vel enim sodales scelerisque. Mauris quisn pellentesque odio, in vulputate turpis. Integer condimentum eni lorem pellentesque euismod. Nam rutrum accumsan nisl vulputate.',1,1,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(4,'How i place a order ?','Aenean condimentum nibh vel enim sodales scelerisque. Mauris quisn pellentesque odio, in vulputate turpis. Integer condimentum eni lorem pellentesque euismod. Nam rutrum accumsan nisl vulputate.',1,1,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(5,'How i make a withdrawal ?','Aenean condimentum nibh vel enim sodales scelerisque. Mauris quisn pellentesque odio, in vulputate turpis. Integer condimentum eni lorem pellentesque euismod. Nam rutrum accumsan nisl vulputate.',1,1,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(6,'What about the deposit process ?','Aenean condimentum nibh vel enim sodales scelerisque. Mauris quisn pellentesque odio, in vulputate turpis. Integer condimentum eni lorem pellentesque euismod. Nam rutrum accumsan nisl vulputate.',1,1,'2022-08-16 07:46:22','2022-08-16 07:46:22');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favourite_coin_pairs`
--

DROP TABLE IF EXISTS `favourite_coin_pairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favourite_coin_pairs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `coin_pairs_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favourite_coin_pairs_user_id_coin_pairs_id_unique` (`user_id`,`coin_pairs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourite_coin_pairs`
--

LOCK TABLES `favourite_coin_pairs` WRITE;
/*!40000 ALTER TABLE `favourite_coin_pairs` DISABLE KEYS */;
/*!40000 ALTER TABLE `favourite_coin_pairs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favourite_order_books`
--

DROP TABLE IF EXISTS `favourite_order_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favourite_order_books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_coin_id` int NOT NULL,
  `trade_coin_id` int NOT NULL,
  `price` decimal(19,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourite_order_books`
--

LOCK TABLES `favourite_order_books` WRITE;
/*!40000 ALTER TABLE `favourite_order_books` DISABLE KEYS */;
/*!40000 ALTER TABLE `favourite_order_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ico_phases`
--

DROP TABLE IF EXISTS `ico_phases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ico_phases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `phase_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `fees` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `bonus` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint NOT NULL DEFAULT '1',
  `affiliation_level` int DEFAULT NULL,
  `affiliation_percentage` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ico_phases`
--

LOCK TABLES `ico_phases` WRITE;
/*!40000 ALTER TABLE `ico_phases` DISABLE KEYS */;
/*!40000 ALTER TABLE `ico_phases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `landing_banners`
--

DROP TABLE IF EXISTS `landing_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landing_banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landing_banners`
--

LOCK TABLES `landing_banners` WRITE;
/*!40000 ALTER TABLE `landing_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `landing_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `landing_features`
--

DROP TABLE IF EXISTS `landing_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landing_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `feature_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landing_features`
--

LOCK TABLES `landing_features` WRITE;
/*!40000 ALTER TABLE `landing_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `landing_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,NULL,'en','2022-08-16 07:46:17','2022-08-16 07:46:17');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_bonus_distribution_histories`
--

DROP TABLE IF EXISTS `membership_bonus_distribution_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membership_bonus_distribution_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `plan_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `membership_id` bigint NOT NULL,
  `distribution_date` date NOT NULL,
  `bonus_coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BTC',
  `bonus_amount_btc` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `bonus_amount` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `plan_current_bonus` decimal(13,8) NOT NULL DEFAULT '0.00000000',
  `bonus_type` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_bonus_distribution_histories`
--

LOCK TABLES `membership_bonus_distribution_histories` WRITE;
/*!40000 ALTER TABLE `membership_bonus_distribution_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_bonus_distribution_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_bonuses`
--

DROP TABLE IF EXISTS `membership_bonuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membership_bonuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `bonus_amount` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_bonuses`
--

LOCK TABLES `membership_bonuses` WRITE;
/*!40000 ALTER TABLE `membership_bonuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_bonuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_clubs`
--

DROP TABLE IF EXISTS `membership_clubs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membership_clubs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `plan_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_clubs`
--

LOCK TABLES `membership_clubs` WRITE;
/*!40000 ALTER TABLE `membership_clubs` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_clubs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_plans`
--

DROP TABLE IF EXISTS `membership_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membership_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_type` tinyint NOT NULL DEFAULT '1',
  `bonus` decimal(13,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_plans`
--

LOCK TABLES `membership_plans` WRITE;
/*!40000 ALTER TABLE `membership_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_transaction_histories`
--

DROP TABLE IF EXISTS `membership_transaction_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membership_transaction_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `type` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_transaction_histories`
--

LOCK TABLES `membership_transaction_histories` WRITE;
/*!40000 ALTER TABLE `membership_transaction_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `membership_transaction_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2018_08_29_200844_create_languages_table',1),(9,'2018_08_29_205156_create_translations_table',1),(10,'2019_06_24_092552_create_wallets_table',1),(11,'2019_07_24_092303_create_user_settings_table',1),(12,'2019_07_24_092331_create_admin_settings_table',1),(13,'2019_07_24_092409_create_activity_logs_table',1),(14,'2019_07_24_092511_create_wallet_address_histories_table',1),(15,'2019_07_24_103207_create_user_verification_codes_table',1),(16,'2019_10_17_075927_create_affiliation_codes_table',1),(17,'2019_10_17_080002_create_affiliation_histories_table',1),(18,'2019_10_17_080031_create_referral_users_table',1),(19,'2019_12_14_000001_create_personal_access_tokens_table',1),(20,'2020_04_29_080822_create_verification_details_table',1),(21,'2020_04_29_081029_create_banks_table',1),(22,'2020_04_29_081134_create_buy_coin_histories_table',1),(23,'2020_04_29_081343_create_deposite_transactions_table',1),(24,'2020_04_29_081451_create_withdraw_histories_table',1),(25,'2020_06_11_133803_create_membership_clubs_table',1),(26,'2020_06_11_134228_create_membership_plans_table',1),(27,'2020_06_11_134611_create_membership_bonuses_table',1),(28,'2020_06_11_134742_create_membership_bonus_distribution_histories_table',1),(29,'2020_06_11_134823_create_membership_transaction_histories_table',1),(30,'2020_06_17_123519_create_faqs_table',1),(31,'2020_06_19_095619_create_send_mail_records_table',1),(32,'2020_06_19_183647_create_notifications_table',1),(33,'2020_06_21_152330_create_referral_sign_bonus_histories_table',1),(34,'2020_06_24_080256_create_websockets_statistics_entries_table',1),(35,'2020_07_01_111249_create_admin_give_coin_histories_table',1),(36,'2020_07_03_092949_create_ico_phases_table',1),(37,'2020_07_06_053213_create_buy_coin_referral_histories_table',1),(38,'2020_07_26_091257_create_coin_requests_table',1),(39,'2020_09_25_105747_create_coins_table',1),(40,'2021_01_13_093659_create_custom_pages_table',1),(41,'2021_01_16_064548_create_contact_us_table',1),(42,'2021_03_04_065920_create_wallet_swap_histories_table',1),(43,'2021_04_19_124055_create_wallet_co_users_table',1),(44,'2021_04_19_125002_create_temp_withdraws_table',1),(45,'2021_04_19_125104_create_co_wallet_withdraw_approvals_table',1),(46,'2021_04_21_093136_create_coin_pairs_table',1),(47,'2021_04_21_102458_create_selected_coin_pairs_table',1),(48,'2021_04_26_072220_create_favourite_coin_pairs_table',1),(49,'2021_04_26_083147_visual_number_format',1),(50,'2021_04_30_083147_create_buys_table',1),(51,'2021_04_30_083148_create_sells_table',1),(52,'2021_04_30_083149_create_transactions_table',1),(53,'2021_04_30_083288_buy_process_not_big_than_amount',1),(54,'2021_04_30_084147_update_transaction_last_price',1),(55,'2021_04_30_085147_sell_process_not_big_than_amount',1),(56,'2021_04_30_086722_create_favourite_order_books_table',1),(57,'2021_05_31_084035_create_condition_buys_table',1),(58,'2021_05_31_084049_create_condition_sells_table',1),(59,'2021_05_31_084657_create_stop_limits_table',1),(60,'2021_11_14_100847_create_tv_chart_15min_table',1),(61,'2021_11_14_100847_create_tv_chart_1day_table',1),(62,'2021_11_14_100847_create_tv_chart_2hours_table',1),(63,'2021_11_14_100847_create_tv_chart_30min_table',1),(64,'2021_11_14_100847_create_tv_chart_4hours_table',1),(65,'2021_11_14_100847_create_tv_chart_5min_table',1),(66,'2021_12_31_100100_create_landing_banners_table',1),(67,'2021_12_31_100126_create_announcements_table',1),(68,'2022_05_05_052425_create_social_media_table',1),(69,'2022_05_05_052840_create_landing_features_table',1),(70,'2022_08_03_074054_add_type_at_custom_page',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_body` longtext COLLATE utf8mb4_unicode_ci,
  `type` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral_sign_bonus_histories`
--

DROP TABLE IF EXISTS `referral_sign_bonus_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referral_sign_bonus_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `parent_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `amount` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral_sign_bonus_histories`
--

LOCK TABLES `referral_sign_bonus_histories` WRITE;
/*!40000 ALTER TABLE `referral_sign_bonus_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `referral_sign_bonus_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral_users`
--

DROP TABLE IF EXISTS `referral_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referral_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `referral_users_user_id_unique` (`user_id`),
  KEY `referral_users_parent_id_foreign` (`parent_id`),
  CONSTRAINT `referral_users_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `referral_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral_users`
--

LOCK TABLES `referral_users` WRITE;
/*!40000 ALTER TABLE `referral_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `referral_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selected_coin_pairs`
--

DROP TABLE IF EXISTS `selected_coin_pairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `selected_coin_pairs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `base_coin_id` int NOT NULL,
  `trade_coin_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selected_coin_pairs_base_coin_id_user_id_trade_coin_id_unique` (`base_coin_id`,`user_id`,`trade_coin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selected_coin_pairs`
--

LOCK TABLES `selected_coin_pairs` WRITE;
/*!40000 ALTER TABLE `selected_coin_pairs` DISABLE KEYS */;
/*!40000 ALTER TABLE `selected_coin_pairs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sells`
--

DROP TABLE IF EXISTS `sells`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sells` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `condition_buy_id` bigint unsigned DEFAULT NULL,
  `trade_coin_id` int NOT NULL,
  `base_coin_id` int NOT NULL,
  `amount` decimal(19,8) unsigned NOT NULL,
  `price` decimal(19,8) unsigned NOT NULL,
  `processed` decimal(19,8) unsigned NOT NULL DEFAULT '0.00000000',
  `virtual_amount` decimal(19,8) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false = pending, true = success',
  `btc_rate` decimal(19,8) NOT NULL,
  `is_market` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = normal, 2 = active',
  `is_conditioned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = simple buy & 1 = condition buy',
  `category` tinyint NOT NULL DEFAULT '1' COMMENT '1 = exchange',
  `maker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `taker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sells`
--

LOCK TABLES `sells` WRITE;
/*!40000 ALTER TABLE `sells` DISABLE KEYS */;
/*!40000 ALTER TABLE `sells` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `sell_process_not_big_than_amount` BEFORE UPDATE ON `sells` FOR EACH ROW BEGIN
                                declare msg varchar(128);
                                if new.processed > OLD.amount then
                                  set msg = concat('Process Not Bigger than Amount');
                                  signal sqlstate '45000' set message_text = msg;
                                end if;
                              END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `send_mail_records`
--

DROP TABLE IF EXISTS `send_mail_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `send_mail_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `email_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `send_mail_records`
--

LOCK TABLES `send_mail_records` WRITE;
/*!40000 ALTER TABLE `send_mail_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `send_mail_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_media`
--

DROP TABLE IF EXISTS `social_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `media_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_media`
--

LOCK TABLES `social_media` WRITE;
/*!40000 ALTER TABLE `social_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stop_limits`
--

DROP TABLE IF EXISTS `stop_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stop_limits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `condition_buy_id` bigint unsigned DEFAULT NULL,
  `trade_coin_id` int NOT NULL,
  `base_coin_id` int NOT NULL,
  `stop` decimal(19,8) NOT NULL,
  `limit_price` decimal(19,8) NOT NULL,
  `amount` decimal(19,8) NOT NULL,
  `order` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_conditioned` tinyint NOT NULL DEFAULT '0' COMMENT '0 = simple stop limits, 1 = advanced stop limit',
  `category` tinyint NOT NULL DEFAULT '1' COMMENT '1 = exchange',
  `maker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `taker_fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stop_limits`
--

LOCK TABLES `stop_limits` WRITE;
/*!40000 ALTER TABLE `stop_limits` DISABLE KEYS */;
/*!40000 ALTER TABLE `stop_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_withdraws`
--

DROP TABLE IF EXISTS `temp_withdraws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temp_withdraws` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `wallet_id` bigint NOT NULL,
  `withdraw_id` bigint DEFAULT NULL,
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_withdraws`
--

LOCK TABLES `temp_withdraws` WRITE;
/*!40000 ALTER TABLE `temp_withdraws` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_withdraws` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_coin_id` int unsigned NOT NULL,
  `trade_coin_id` int unsigned NOT NULL,
  `buy_id` bigint unsigned NOT NULL COMMENT 'exchange_buy_id',
  `sell_id` bigint unsigned NOT NULL COMMENT 'exchange_sell_id',
  `buy_user_id` bigint unsigned NOT NULL,
  `sell_user_id` bigint unsigned NOT NULL,
  `price_order_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(19,8) unsigned NOT NULL,
  `price` decimal(19,8) unsigned NOT NULL,
  `last_price` decimal(19,8) unsigned DEFAULT NULL,
  `btc_rate` decimal(19,8) unsigned NOT NULL,
  `btc` decimal(19,8) unsigned NOT NULL,
  `total` decimal(29,18) unsigned NOT NULL,
  `buy_fees` decimal(29,18) unsigned NOT NULL,
  `sell_fees` decimal(29,18) unsigned NOT NULL,
  `remove_from_chart` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_transaction_last_price` BEFORE INSERT ON `transactions` FOR EACH ROW BEGIN
              SET NEW.last_price = (select price from transactions 
              where 
                base_coin_id = NEW.base_coin_id 
                  and 
                trade_coin_id = NEW.trade_coin_id
                order by created_at desc limit 1 );
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int unsigned NOT NULL,
  `group` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `translations_language_id_foreign` (`language_id`),
  CONSTRAINT `translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tv_chart_15mins`
--

DROP TABLE IF EXISTS `tv_chart_15mins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tv_chart_15mins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `interval` int unsigned NOT NULL,
  `base_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open` decimal(19,8) NOT NULL,
  `close` decimal(19,8) NOT NULL,
  `high` decimal(19,8) NOT NULL,
  `low` decimal(19,8) NOT NULL,
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tv_chart_15mins_base_coin_id_trade_coin_id_interval_unique` (`base_coin_id`,`trade_coin_id`,`interval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tv_chart_15mins`
--

LOCK TABLES `tv_chart_15mins` WRITE;
/*!40000 ALTER TABLE `tv_chart_15mins` DISABLE KEYS */;
/*!40000 ALTER TABLE `tv_chart_15mins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tv_chart_1days`
--

DROP TABLE IF EXISTS `tv_chart_1days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tv_chart_1days` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `interval` int unsigned NOT NULL,
  `base_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open` decimal(19,8) NOT NULL,
  `close` decimal(19,8) NOT NULL,
  `high` decimal(19,8) NOT NULL,
  `low` decimal(19,8) NOT NULL,
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tv_chart_1days_base_coin_id_trade_coin_id_interval_unique` (`base_coin_id`,`trade_coin_id`,`interval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tv_chart_1days`
--

LOCK TABLES `tv_chart_1days` WRITE;
/*!40000 ALTER TABLE `tv_chart_1days` DISABLE KEYS */;
/*!40000 ALTER TABLE `tv_chart_1days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tv_chart_2hours`
--

DROP TABLE IF EXISTS `tv_chart_2hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tv_chart_2hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `interval` int unsigned NOT NULL,
  `base_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open` decimal(19,8) NOT NULL,
  `close` decimal(19,8) NOT NULL,
  `high` decimal(19,8) NOT NULL,
  `low` decimal(19,8) NOT NULL,
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tv_chart_2hours_base_coin_id_trade_coin_id_interval_unique` (`base_coin_id`,`trade_coin_id`,`interval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tv_chart_2hours`
--

LOCK TABLES `tv_chart_2hours` WRITE;
/*!40000 ALTER TABLE `tv_chart_2hours` DISABLE KEYS */;
/*!40000 ALTER TABLE `tv_chart_2hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tv_chart_30mins`
--

DROP TABLE IF EXISTS `tv_chart_30mins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tv_chart_30mins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `interval` int unsigned NOT NULL,
  `base_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open` decimal(19,8) NOT NULL,
  `close` decimal(19,8) NOT NULL,
  `high` decimal(19,8) NOT NULL,
  `low` decimal(19,8) NOT NULL,
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tv_chart_30mins_base_coin_id_trade_coin_id_interval_unique` (`base_coin_id`,`trade_coin_id`,`interval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tv_chart_30mins`
--

LOCK TABLES `tv_chart_30mins` WRITE;
/*!40000 ALTER TABLE `tv_chart_30mins` DISABLE KEYS */;
/*!40000 ALTER TABLE `tv_chart_30mins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tv_chart_4hours`
--

DROP TABLE IF EXISTS `tv_chart_4hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tv_chart_4hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `interval` int unsigned NOT NULL,
  `base_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open` decimal(19,8) NOT NULL,
  `close` decimal(19,8) NOT NULL,
  `high` decimal(19,8) NOT NULL,
  `low` decimal(19,8) NOT NULL,
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tv_chart_4hours_base_coin_id_trade_coin_id_interval_unique` (`base_coin_id`,`trade_coin_id`,`interval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tv_chart_4hours`
--

LOCK TABLES `tv_chart_4hours` WRITE;
/*!40000 ALTER TABLE `tv_chart_4hours` DISABLE KEYS */;
/*!40000 ALTER TABLE `tv_chart_4hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tv_chart_5mins`
--

DROP TABLE IF EXISTS `tv_chart_5mins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tv_chart_5mins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `interval` int unsigned NOT NULL,
  `base_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_coin_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open` decimal(19,8) NOT NULL,
  `close` decimal(19,8) NOT NULL,
  `high` decimal(19,8) NOT NULL,
  `low` decimal(19,8) NOT NULL,
  `volume` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tv_chart_5mins_base_coin_id_trade_coin_id_interval_unique` (`base_coin_id`,`trade_coin_id`,`interval`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tv_chart_5mins`
--

LOCK TABLES `tv_chart_5mins` WRITE;
/*!40000 ALTER TABLE `tv_chart_5mins` DISABLE KEYS */;
/*!40000 ALTER TABLE `tv_chart_5mins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_settings_user_id_foreign` (`user_id`),
  CONSTRAINT `user_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_verification_codes`
--

DROP TABLE IF EXISTS `user_verification_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_verification_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `type` tinyint NOT NULL DEFAULT '1',
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_verification_codes`
--

LOCK TABLES `user_verification_codes` WRITE;
/*!40000 ALTER TABLE `user_verification_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_verification_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_code` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int NOT NULL DEFAULT '2',
  `status` int NOT NULL DEFAULT '1',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified` tinyint NOT NULL DEFAULT '0',
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint NOT NULL DEFAULT '1',
  `birth_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `g2f_enabled` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `google2fa_secret` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `device_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` tinyint NOT NULL DEFAULT '1',
  `push_notification_status` tinyint NOT NULL DEFAULT '1',
  `email_notification_status` tinyint NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_reset_code_unique` (`reset_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mr.','Admin','admin@email.com',NULL,1,1,NULL,0,NULL,1,NULL,NULL,'0',NULL,1,'$2y$10$tzKE6tSYM4JtHTeBaP/ja.lvZKc2uVcy1iZBiN9FMhCzU4dQFWqha','en',NULL,1,1,1,NULL,'2022-08-16 07:46:21','2022-08-16 07:46:21'),(2,'Mr','User','user@email.com',NULL,2,1,NULL,0,NULL,1,NULL,NULL,'0',NULL,1,'$2y$10$Au3f26Dn0sqS7t83PBr61.lgOwjCXbGkJYuEhZluL1YucCnoFQ8UC','en',NULL,1,1,1,NULL,'2022-08-16 07:46:21','2022-08-16 07:46:21');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_details`
--

DROP TABLE IF EXISTS `verification_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verification_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `field_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verification_details_user_id_foreign` (`user_id`),
  CONSTRAINT `verification_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_details`
--

LOCK TABLES `verification_details` WRITE;
/*!40000 ALTER TABLE `verification_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `verification_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_address_histories`
--

DROP TABLE IF EXISTS `wallet_address_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_address_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BTC',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_address_histories`
--

LOCK TABLES `wallet_address_histories` WRITE;
/*!40000 ALTER TABLE `wallet_address_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_address_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_co_users`
--

DROP TABLE IF EXISTS `wallet_co_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_co_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_co_users`
--

LOCK TABLES `wallet_co_users` WRITE;
/*!40000 ALTER TABLE `wallet_co_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_co_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_swap_histories`
--

DROP TABLE IF EXISTS `wallet_swap_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_swap_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `from_wallet_id` bigint NOT NULL,
  `to_wallet_id` bigint NOT NULL,
  `from_coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `converted_amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_swap_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `wallet_swap_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_swap_histories`
--

LOCK TABLES `wallet_swap_histories` WRITE;
/*!40000 ALTER TABLE `wallet_swap_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_swap_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coin_id` bigint NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '1',
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `is_primary` tinyint NOT NULL DEFAULT '0',
  `balance` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallets_user_id_foreign` (`user_id`),
  CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (1,1,'BTC Wallet',1,NULL,1,'BTC',1,0,0.000000000000000000,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(2,1,'USDT Wallet',2,NULL,1,'USDT',1,0,0.000000000000000000,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(3,2,'BTC Wallet',1,NULL,1,'BTC',1,0,0.000000000000000000,'2022-08-16 07:46:22','2022-08-16 07:46:22'),(4,2,'USDT Wallet',2,NULL,1,'USDT',1,0,0.000000000000000000,'2022-08-16 07:46:22','2022-08-16 07:46:22');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websockets_statistics_entries`
--

DROP TABLE IF EXISTS `websockets_statistics_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websockets_statistics_entries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websockets_statistics_entries`
--

LOCK TABLES `websockets_statistics_entries` WRITE;
/*!40000 ALTER TABLE `websockets_statistics_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `websockets_statistics_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdraw_histories`
--

DROP TABLE IF EXISTS `withdraw_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdraw_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `wallet_id` bigint unsigned NOT NULL,
  `amount` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `btc` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `doller` decimal(19,8) NOT NULL DEFAULT '0.00000000',
  `address_type` tinyint NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_hash` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coin_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BTC',
  `receiver_wallet_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmations` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fees` decimal(29,18) NOT NULL DEFAULT '0.000000000000000000',
  `status` tinyint NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdraw_histories_wallet_id_foreign` (`wallet_id`),
  CONSTRAINT `withdraw_histories_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraw_histories`
--

LOCK TABLES `withdraw_histories` WRITE;
/*!40000 ALTER TABLE `withdraw_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdraw_histories` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-16 19:48:30
