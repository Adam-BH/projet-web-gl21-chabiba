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

-- hiking guide trails (kept empty; the application falls back to built-in sample data if needed)
CREATE TABLE IF NOT EXISTS `trails` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `level` VARCHAR(20) NOT NULL,
  `min_age` INT DEFAULT 0,
  `max_group_size` INT DEFAULT NULL,
  `video_url` VARCHAR(255),
  `details` TEXT,
  `tips` TEXT,
  `checklist` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample data
INSERT INTO `camping_sites` (`name`, `description`, `capacity`, `city`, `lat`, `lon`, `image`) VALUES
('Zitouna Camp', 'Seaside camping near the olive groves of Sahel with easy beach access.', 50, 'Sousse', 35.8288, 10.6405, ''),
('Dougga Hill Camp', 'Hillside campsite overlooking the Roman ruins of Dougga, ideal for history lovers.', 35, 'Dougga', 36.4214, 9.2197, ''),
('Ichkeul Lake Camp', 'Lakeside camping near Ichkeul National Park, great for birdwatching.', 40, 'Bizerte', 37.1667, 9.6667, ''),
('Djerba Palm Camp', 'Palm-shaded pitches on the island of Djerba with coastal trails.', 60, 'Djerba', 33.7833, 10.8833, ''),
('Chenini Village Camp', 'Berber village campsite in the mountains near Tataouine.', 25, 'Tataouine', 32.9167, 10.3833, ''),
('Kroumirie Forest Camp', 'Deep forest camping in the Kroumirie mountains with hiking access.', 30, 'Jendouba', 36.5, 8.75, ''),
('Sidi Saad Dam Camp', 'Lakeside camping near Sidi Saad Dam, popular for fishing and picnics.', 45, 'Kairouan', 35.5, 9.8, ''),
('Cap Bon Seaside Camp', 'Coastal campsite on Cap Bon with views of the Mediterranean.', 55, 'Nabeul', 36.4561, 10.7378, ''),
('Matmata underground Camp', 'Unique troglodyte-style camping near the underground homes of Matmata.', 20, 'Gabes', 33.55, 9.9667, ''),
('Tamerza Canyon Camp', 'Canyon-side camping near Tamerza oasis with waterfall hikes.', 28, 'Tamerza', 34.3833, 7.8667, '');

-- sample images (paths are examples; place images in assets/Images and update paths accordingly)
INSERT INTO `camping_site_images` (`site_id`, `path`, `sort_order`) VALUES
(1, 'assets/Images/zitouna-1.jpg', 0),
(2, 'assets/Images/dougga-1.jpg', 0),
(3, 'assets/Images/ichkeul-1.jpg', 0),
(4, 'assets/Images/djerba-1.jpg', 0),
(5, 'assets/Images/chenini-1.jpg', 0),
(6, 'assets/Images/kroumirie-1.jpg', 0),
(7, 'assets/Images/sidi-saad-1.jpg', 0),
(8, 'assets/Images/capbon-1.jpg', 0),
(9, 'assets/Images/matmata-1.jpg', 0),
(10, 'assets/Images/tamerza-1.jpg', 0);