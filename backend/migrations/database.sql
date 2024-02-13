-- my_hiking_map.district definition
CREATE TABLE `district` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.doctrine_migration_versions definition
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_unicode_ci;

-- my_hiking_map.file definition
CREATE TABLE `file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_unique_url` (`url`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.landmark_type definition
CREATE TABLE `landmark_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.`user` definition
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.county definition
CREATE TABLE `county` (
  `id` int NOT NULL AUTO_INCREMENT,
  `district_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `county_district_FK` (`district_id`),
  CONSTRAINT `county_district_FK` FOREIGN KEY (`district_id`) REFERENCES `district` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.location definition
CREATE TABLE `location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `county_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_county_FK` (`county_id`),
  CONSTRAINT `location_county_FK` FOREIGN KEY (`county_id`) REFERENCES `county` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.landmark definition
CREATE TABLE `landmark` (
  `id` int NOT NULL AUTO_INCREMENT,
  `landmark_type_id` int DEFAULT NULL,
  `track_id` int DEFAULT NULL,
  `file_id` int DEFAULT NULL,
  `point_id` int DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `landmark_file_FK` (`file_id`),
  KEY `landmark_track_FK` (`track_id`),
  KEY `landmark_landmark_type_FK` (`landmark_type_id`),
  KEY `landmark_point_FK` (`point_id`),
  CONSTRAINT `landmark_file_FK` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `landmark_landmark_type_FK` FOREIGN KEY (`landmark_type_id`) REFERENCES `landmark_type` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `landmark_point_FK` FOREIGN KEY (`point_id`) REFERENCES `point` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `landmark_track_FK` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.`point` definition
CREATE TABLE `point` (
  `id` int NOT NULL AUTO_INCREMENT,
  `track_id` int DEFAULT NULL,
  `elevation` float NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `point_track_FK` (`track_id`),
  CONSTRAINT `point_track_FK` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.track definition
CREATE TABLE `track` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_id` int DEFAULT NULL,
  `start_location_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distance` float DEFAULT NULL,
  `slope` float DEFAULT NULL,
  `route_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` smallint DEFAULT NULL,
  `landscape` smallint DEFAULT NULL,
  `enjoyment` smallint DEFAULT NULL,
  `track_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guide` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `week_number` smallint DEFAULT NULL,
  `is_moita` tinyint(1) NOT NULL DEFAULT '0',
  `duration` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `track_track_location_FK` (`start_location_id`),
  KEY `track_file_FK` (`file_id`),
  CONSTRAINT `track_file_FK` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `track_track_location_FK` FOREIGN KEY (`start_location_id`) REFERENCES `track_location` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- my_hiking_map.track_location definition
CREATE TABLE `track_location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `track_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `track_location_track_FK` (`track_id`),
  KEY `track_location_location_FK` (`location_id`),
  CONSTRAINT `track_location_location_FK` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `track_location_track_FK` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;