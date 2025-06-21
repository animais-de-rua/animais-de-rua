/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `adopters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adopters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adopters_territory_id_foreign` (`territory_id`),
  KEY `adopters_user_id_foreign` (`user_id`),
  CONSTRAINT `adopters_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `adopters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `adoptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adoptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `process_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `fat_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_after` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int unsigned NOT NULL DEFAULT '0',
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `microchip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sterilized` tinyint(1) NOT NULL DEFAULT '0',
  `vaccinated` tinyint(1) NOT NULL DEFAULT '0',
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `individual` tinyint(1) NOT NULL DEFAULT '0',
  `docile` tinyint(1) NOT NULL DEFAULT '0',
  `abandoned` tinyint(1) NOT NULL DEFAULT '0',
  `foal` tinyint(1) NOT NULL DEFAULT '0',
  `images` text COLLATE utf8mb4_unicode_ci,
  `features` text COLLATE utf8mb4_unicode_ci,
  `history` text COLLATE utf8mb4_unicode_ci,
  `adoption_date` date NOT NULL,
  `adopter_id` bigint unsigned DEFAULT NULL,
  `status` enum('open','pending','closed','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adoptions_process_id_foreign` (`process_id`),
  KEY `adoptions_user_id_foreign` (`user_id`),
  KEY `adoptions_fat_id_foreign` (`fat_id`),
  KEY `adoptions_adopter_id_foreign` (`adopter_id`),
  CONSTRAINT `adoptions_adopter_id_foreign` FOREIGN KEY (`adopter_id`) REFERENCES `adopters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `adoptions_fat_id_foreign` FOREIGN KEY (`fat_id`) REFERENCES `fats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `adoptions_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `adoptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `process_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `vet_id_1` bigint unsigned DEFAULT NULL,
  `date_1` date DEFAULT NULL,
  `vet_id_2` bigint unsigned DEFAULT NULL,
  `date_2` date DEFAULT NULL,
  `amount_males` int unsigned NOT NULL DEFAULT '0',
  `amount_females` int unsigned NOT NULL DEFAULT '0',
  `amount_other` int unsigned NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `notes_deliver` text COLLATE utf8mb4_unicode_ci,
  `notes_collect` text COLLATE utf8mb4_unicode_ci,
  `notes_contact` text COLLATE utf8mb4_unicode_ci,
  `notes_godfather` text COLLATE utf8mb4_unicode_ci,
  `notes_info` text COLLATE utf8mb4_unicode_ci,
  `status` enum('approving','approved_option_1','approved_option_2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approving',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_process_id_foreign` (`process_id`),
  KEY `appointments_user_id_foreign` (`user_id`),
  KEY `appointments_vet_id_1_foreign` (`vet_id_1`),
  KEY `appointments_vet_id_2_foreign` (`vet_id_2`),
  CONSTRAINT `appointments_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_vet_id_1_foreign` FOREIGN KEY (`vet_id_1`) REFERENCES `vets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_vet_id_2_foreign` FOREIGN KEY (`vet_id_2`) REFERENCES `vets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `introduction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `lft` int unsigned DEFAULT NULL,
  `rgt` int unsigned DEFAULT NULL,
  `depth` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `donations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `process_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `godfather_id` bigint unsigned DEFAULT NULL,
  `headquarter_id` bigint unsigned DEFAULT NULL,
  `protocol_id` bigint unsigned DEFAULT NULL,
  `type` enum('private','headquarter','protocol') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(8,2) unsigned DEFAULT '0.00',
  `date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donations_process_id_foreign` (`process_id`),
  KEY `donations_user_id_foreign` (`user_id`),
  KEY `donations_godfather_id_foreign` (`godfather_id`),
  KEY `donations_headquarter_id_foreign` (`headquarter_id`),
  KEY `donations_protocol_id_foreign` (`protocol_id`),
  CONSTRAINT `donations_godfather_id_foreign` FOREIGN KEY (`godfather_id`) REFERENCES `godfathers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donations_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donations_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donations_protocol_id_foreign` FOREIGN KEY (`protocol_id`) REFERENCES `protocols` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fats_territory_id_foreign` (`territory_id`),
  KEY `fats_user_id_foreign` (`user_id`),
  CONSTRAINT `fats_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fats_headquarters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fats_headquarters` (
  `fat_id` bigint unsigned NOT NULL,
  `headquarter_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`fat_id`,`headquarter_id`),
  KEY `fats_headquarters_headquarter_id_foreign` (`headquarter_id`),
  CONSTRAINT `fats_headquarters_fat_id_foreign` FOREIGN KEY (`fat_id`) REFERENCES `fats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fats_headquarters_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `friend_card_modalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friend_card_modalities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int unsigned NOT NULL DEFAULT '0',
  `type` enum('monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `godfathers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `godfathers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `godfathers_territory_id_foreign` (`territory_id`),
  KEY `godfathers_user_id_foreign` (`user_id`),
  CONSTRAINT `godfathers_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `godfathers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `godfathers_headquarters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `godfathers_headquarters` (
  `godfather_id` bigint unsigned NOT NULL,
  `headquarter_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`godfather_id`,`headquarter_id`),
  KEY `godfathers_headquarters_headquarter_id_foreign` (`headquarter_id`),
  CONSTRAINT `godfathers_headquarters_godfather_id_foreign` FOREIGN KEY (`godfather_id`) REFERENCES `godfathers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `godfathers_headquarters_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `headquarters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `headquarters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `headquarters_territories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `headquarters_territories` (
  `headquarter_id` bigint unsigned NOT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`headquarter_id`,`territory_id`),
  KEY `headquarters_territories_territory_id_foreign` (`territory_id`),
  CONSTRAINT `headquarters_territories_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `headquarters_territories_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `headquarters_territories_range`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `headquarters_territories_range` (
  `headquarter_id` bigint unsigned NOT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`headquarter_id`,`territory_id`),
  KEY `headquarters_territories_range_territory_id_foreign` (`territory_id`),
  CONSTRAINT `headquarters_territories_range_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `headquarters_territories_range_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `language_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `language_lines_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abbr` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `script` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `native` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `extras` longtext COLLATE utf8mb4_unicode_ci,
  `extras_translatable` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partner_category_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_category_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` text COLLATE utf8mb4_unicode_ci,
  `phone1_info` text COLLATE utf8mb4_unicode_ci,
  `phone2` text COLLATE utf8mb4_unicode_ci,
  `phone2_info` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `address_info` text COLLATE utf8mb4_unicode_ci,
  `latlong` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `benefit` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_code` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partners_email_unique` (`email`),
  KEY `partners_user_id_foreign` (`user_id`),
  CONSTRAINT `partners_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partners_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners_categories` (
  `partner_id` bigint unsigned NOT NULL,
  `partner_category_list_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`partner_id`,`partner_category_list_id`),
  KEY `partners_categories_partner_category_list_id_foreign` (`partner_category_list_id`),
  CONSTRAINT `partners_categories_partner_category_list_id_foreign` FOREIGN KEY (`partner_category_list_id`) REFERENCES `partner_category_list` (`id`),
  CONSTRAINT `partners_categories_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `partners_territories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners_territories` (
  `partner_id` bigint unsigned NOT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`partner_id`,`territory_id`),
  KEY `partners_territories_territory_id_foreign` (`territory_id`),
  CONSTRAINT `partners_territories_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partners_territories_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `processes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headquarter_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `specie` enum('dog','cat','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dog',
  `amount_males` int unsigned NOT NULL DEFAULT '0',
  `amount_females` int unsigned NOT NULL DEFAULT '0',
  `amount_other` int unsigned NOT NULL DEFAULT '0',
  `status` enum('approving','waiting_godfather','waiting_capture','open','closed','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approving',
  `urgent` tinyint(1) NOT NULL DEFAULT '0',
  `history` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `latlong` text COLLATE utf8mb4_unicode_ci,
  `images` text COLLATE utf8mb4_unicode_ci,
  `contacted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `processes_territory_id_foreign` (`territory_id`),
  KEY `processes_headquarter_id_foreign` (`headquarter_id`),
  KEY `processes_user_id_foreign` (`user_id`),
  CONSTRAINT `processes_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `processes_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `processes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `protocol_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `protocol_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `council` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `process_id` bigint unsigned DEFAULT NULL,
  `protocol_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `protocol_requests_email_unique` (`email`),
  KEY `protocol_requests_territory_id_foreign` (`territory_id`),
  KEY `protocol_requests_process_id_foreign` (`process_id`),
  KEY `protocol_requests_protocol_id_foreign` (`protocol_id`),
  KEY `protocol_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `protocol_requests_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `protocol_requests_protocol_id_foreign` FOREIGN KEY (`protocol_id`) REFERENCES `protocols` (`id`) ON DELETE CASCADE,
  CONSTRAINT `protocol_requests_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `protocol_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `protocols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `protocols` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `territory_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `headquarter_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `protocols_email_unique` (`email`),
  KEY `protocols_territory_id_foreign` (`territory_id`),
  KEY `protocols_headquarter_id_foreign` (`headquarter_id`),
  KEY `protocols_user_id_foreign` (`user_id`),
  CONSTRAINT `protocols_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `protocols_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `protocols_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `field` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `lft` int unsigned DEFAULT NULL,
  `rgt` int unsigned DEFAULT NULL,
  `depth` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cart` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `expense` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `payment` enum('bank_transfer','paypal','mbway','credit_card') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank_transfer',
  `receipt` text COLLATE utf8mb4_unicode_ci,
  `invoice` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('waiting','in_progress','shipped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `store_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_orders_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_orders_products` (
  `store_product_id` bigint unsigned DEFAULT NULL,
  `store_order_id` bigint unsigned DEFAULT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `discount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `discount_no_vat` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  UNIQUE KEY `store_orders_products_store_product_id_store_order_id_unique` (`store_product_id`,`store_order_id`),
  KEY `store_orders_products_store_order_id_foreign` (`store_order_id`),
  CONSTRAINT `store_orders_products_store_order_id_foreign` FOREIGN KEY (`store_order_id`) REFERENCES `store_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_orders_products_store_product_id_foreign` FOREIGN KEY (`store_product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_petsitting_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_petsitting_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat` tinyint unsigned DEFAULT NULL,
  `price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `price_no_vat` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `expense` decimal(8,2) unsigned DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `store_product_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_stocks_user_id_foreign` (`user_id`),
  KEY `store_stocks_store_product_id_foreign` (`store_product_id`),
  CONSTRAINT `store_stocks_store_product_id_foreign` FOREIGN KEY (`store_product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_stocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `store_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `invoice` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `store_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_order_id` bigint unsigned DEFAULT NULL,
  `store_product_id` bigint unsigned DEFAULT NULL,
  `invoice` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('waiting_payment','paid_out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_store_order_id_foreign` (`store_order_id`),
  KEY `suppliers_store_product_id_foreign` (`store_product_id`),
  CONSTRAINT `suppliers_store_order_id_foreign` FOREIGN KEY (`store_order_id`) REFERENCES `store_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `suppliers_store_product_id_foreign` FOREIGN KEY (`store_product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `territories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `territories` (
  `id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `territories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `territories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `treatment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatment_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_time` int unsigned DEFAULT '60',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `treatments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `treatment_type_id` bigint unsigned DEFAULT NULL,
  `vet_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `affected_animals` int unsigned NOT NULL DEFAULT '1',
  `affected_animals_new` int unsigned NOT NULL DEFAULT '0',
  `expense` decimal(8,2) unsigned DEFAULT '0.00',
  `status` enum('approving','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approving',
  `date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatments_appointment_id_foreign` (`appointment_id`),
  KEY `treatments_treatment_type_id_foreign` (`treatment_type_id`),
  KEY `treatments_vet_id_foreign` (`vet_id`),
  KEY `treatments_user_id_foreign` (`user_id`),
  CONSTRAINT `treatments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatments_treatment_type_id_foreign` FOREIGN KEY (`treatment_type_id`) REFERENCES `treatment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treatments_vet_id_foreign` FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`user_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`user_id`,`model_type`),
  CONSTRAINT `user_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`user_id`,`model_type`),
  CONSTRAINT `user_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `friend_card_modality_id` bigint unsigned DEFAULT NULL,
  `friend_card_number` int unsigned DEFAULT NULL,
  `friend_card_expiry` int unsigned DEFAULT NULL,
  `petsitting_role` enum('Dog','Cat','Both','Others') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `petsitting_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `petsitting_image` mediumtext COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialite` json DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_friend_card_modality_id_foreign` (`friend_card_modality_id`),
  CONSTRAINT `users_friend_card_modality_id_foreign` FOREIGN KEY (`friend_card_modality_id`) REFERENCES `friend_card_modalities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users_headquarters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_headquarters` (
  `user_id` bigint unsigned NOT NULL,
  `headquarter_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`headquarter_id`),
  KEY `users_headquarters_headquarter_id_foreign` (`headquarter_id`),
  CONSTRAINT `users_headquarters_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_headquarters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latlong` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vets_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vets_headquarters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vets_headquarters` (
  `vet_id` bigint unsigned NOT NULL,
  `headquarter_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`vet_id`,`headquarter_id`),
  KEY `vets_headquarters_headquarter_id_foreign` (`headquarter_id`),
  CONSTRAINT `vets_headquarters_headquarter_id_foreign` FOREIGN KEY (`headquarter_id`) REFERENCES `headquarters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vets_headquarters_vet_id_foreign` FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` decimal(8,2) unsigned DEFAULT NULL,
  `percent` tinyint unsigned DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `status` enum('used','unused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unused',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'0001_01_01_000003_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2015_08_04_131614_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2015_09_07_190535_create_languages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2016_05_10_130540_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2016_05_25_121918_create_pages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2018_06_09_102829_create_headquarters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2018_06_10_160816_create_territories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2018_06_21_221038_create_processes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2018_07_05_180852_create_headquarter_territories',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2018_07_08_095410_create_protocols_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2018_07_08_095415_create_godfathers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2018_07_28_225708_create_vets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2018_07_29_105247_create_appointments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2018_07_29_105248_create_treatments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2018_08_25_184800_create_adopters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2018_08_25_184802_create_fats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2018_08_25_184804_create_adoptions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2018_08_31_105753_create_friend_card_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2018_09_01_105322_create_partners_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2018_09_22_193605_create_campaigns_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2018_10_19_224606_create_sponsors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2018_12_29_175615_create_users_headquarters',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2019_01_04_001142_create_vets_headquarters',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2019_01_08_220016_create_godfathers_headquarters',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2019_03_03_003340_create_fats_headquarters',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2019_03_03_012459_create_store_management_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2019_08_06_111305_create_store_stock_and_transactions_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2019_08_16_154849_create_suppliers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2020_01_09_103829_create_vouchers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2024_03_22_014252_add_petsitting_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2024_03_27_143404_create_store_petsitting_requests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2024_04_02_165233_create_language_lines_table',1);
