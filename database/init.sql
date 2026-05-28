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

-- images for camping sites (support multiple photos per site)
CREATE TABLE IF NOT EXISTS `camping_site_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `site_id` INT NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `sort_order` INT DEFAULT 0,
  CONSTRAINT `fk_csi_site` FOREIGN KEY (`site_id`) REFERENCES `camping_sites`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- bookings table: stores reservations for camping sites
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `site_id` INT NOT NULL,
  `user_id` VARCHAR(50) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `people` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_bookings_site` FOREIGN KEY (`site_id`) REFERENCES `camping_sites`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- sample data
INSERT INTO `camping_sites` (`name`, `description`, `capacity`, `city`, `lat`, `lon`, `image`) VALUES
('Lake View Camp', 'Nice campsite by the lake.', 60, 'Lakecity', 45.123, 3.123, ''),
('Pine Forest Camp', 'Shaded tentsites among pines.', 40, 'Forestville', 46.234, 4.234, ''),
('Riverside Meadow', 'Open meadow sites next to a slow river, great for families.', 80, 'Meadowville', 45.532, 3.432, ''),
('Highland Ridge', 'Quiet ridge-top sites with panoramic views and wind-sheltered pitches.', 30, 'Ridgeton', 46.001, 4.001, ''),
('Sunny Glen', 'South-facing glen with easy access to walking trails.', 50, 'Glenburg', 44.981, 3.987, ''),
('Oak Hollow Camp', 'Under oak canopy with picnic areas and fire rings.', 35, 'Oaktown', 45.221, 3.554, '');

-- sample images (paths are examples; place images in assets/Images and update paths accordingly)
INSERT INTO `camping_site_images` (`site_id`, `path`, `sort_order`) VALUES
(1, 'assets/Images/lakeview-1.jpg', 0),
(1, 'assets/Images/lakeview-2.jpg', 1),
(2, 'assets/Images/pine-1.jpg', 0),
(3, 'assets/Images/river-1.jpg', 0),
(4, 'assets/Images/highland-1.jpg', 0),
(5, 'assets/Images/sunny-1.jpg', 0),
(6, 'assets/Images/oak-1.jpg', 0);