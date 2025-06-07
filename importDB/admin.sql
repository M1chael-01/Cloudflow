-- Create the database if it does not exist
CREATE DATABASE IF NOT EXISTS cloudflow_admin;
USE cloudflow_admin;

-- Create the `admin` table
CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,  -- Ensure AUTO_INCREMENT is set here
  `username` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `token` TEXT NOT NULL,
  PRIMARY KEY (`id`)  -- Primary key already defined here
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Commit changes
COMMIT;
