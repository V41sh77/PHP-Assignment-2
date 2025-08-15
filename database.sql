CREATE DATABASE IF NOT EXISTS grocery_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE grocery_app;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  avatar_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(60) DEFAULT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  details TEXT,
  image_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_items_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed admin (email/password below). Change after first login.
INSERT INTO users (name,email,password_hash,is_admin) VALUES
('Store Admin','admin@grocer.ease','$2y$10$AcQf7x0c5b3k2IYz2K3fQe2r1aYyqgZx9cZb5qS8UQxj4jSgq1e7Cy',1)
ON DUPLICATE KEY UPDATE email=email;
-- Password = Admin123!
