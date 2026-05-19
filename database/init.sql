-- init.sql: initialize database and tables for projet-web-gl21-chabiba
CREATE DATABASE IF NOT EXISTS `hiki` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hiki`;

-- adresses
CREATE TABLE IF NOT EXISTS `adresses` (
  `id` VARCHAR(50) PRIMARY KEY,
  `lat` DOUBLE,
  `lon` DOUBLE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- users
CREATE TABLE IF NOT EXISTS `users` (
  `id` VARCHAR(50) PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `city` VARCHAR(50),
  CONSTRAINT `fk_users_adresses` FOREIGN KEY (`city`) REFERENCES `adresses`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `finder` VARCHAR(50) NOT NULL,
  `item` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `place` VARCHAR(50),
  `phone` VARCHAR(15) NOT NULL,
  `picture` VARCHAR(255),
  CONSTRAINT `fk_posts_users` FOREIGN KEY (`finder`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- camping_sites
CREATE TABLE IF NOT EXISTS `camping_sites` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `capacity` INT DEFAULT 0,
  `city` VARCHAR(100),
  `lat` DOUBLE,
  `lon` DOUBLE,
  `image` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample data
INSERT INTO `camping_sites` (`name`, `description`, `capacity`, `city`, `lat`, `lon`, `image`) VALUES
('Lake View Camp', 'Nice campsite by the lake.', 60, 'Lakecity', 45.123, 3.123, ''),
('Pine Forest Camp', 'Shaded tentsites among pines.', 40, 'Forestville', 46.234, 4.234, ''),
('Riverside Meadow', 'Open meadow sites next to a slow river, great for families.', 80, 'Meadowville', 45.532, 3.432, ''),
('Highland Ridge', 'Quiet ridge-top sites with panoramic views and wind-sheltered pitches.', 30, 'Ridgeton', 46.001, 4.001, ''),
('Sunny Glen', 'South-facing glen with easy access to walking trails.', 50, 'Glenburg', 44.981, 3.987, ''),
('Oak Hollow Camp', 'Under oak canopy with picnic areas and fire rings.', 35, 'Oaktown', 45.221, 3.554, '');
