-- =============================================================
-- infosec_lab_secured.sql  –  Normalized & Secured Schema
-- =============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Drop existing tables (order matters due to FK constraints)
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `users`;

-- -------------------------------------------------------------
-- Table: users
-- Passwords stored as bcrypt hashes (password_hash() in PHP)
-- -------------------------------------------------------------
CREATE TABLE `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,   -- bcrypt hash; 255 chars minimum
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default admin – username: admin / password: admin123
INSERT INTO `users` (`username`, `password`) VALUES
('admin', '$2y$12$I9Y1GYR6oCONEGcAJXLaTOtO.BtULx4R.TfB3NFzV4k/f3gXk4MCa');

-- -------------------------------------------------------------
-- Table: courses  (extracted to remove redundancy)
-- course_description is stored ONCE here, not repeated per student
-- -------------------------------------------------------------
CREATE TABLE `courses` (
  `id`                 INT(11)      NOT NULL AUTO_INCREMENT,
  `course_name`        VARCHAR(100) NOT NULL UNIQUE,
  `course_description` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `courses` (`course_name`, `course_description`) VALUES
('BSIT',  'Bachelor of Science in Information Technology'),
('BSCS',  'Bachelor of Science in Computer Science'),
('BSIS',  'Bachelor of Science in Information Systems'),
('BSCPE', 'Bachelor of Science in Computer Engineering');

-- -------------------------------------------------------------
-- Table: students  (course_id is a FK to courses – normalized)
-- course and course_description columns removed (no redundancy)
-- -------------------------------------------------------------
CREATE TABLE `students` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `student_id` VARCHAR(50)  NOT NULL UNIQUE,
  `fullname`   VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `course_id`  INT(11)      NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_students_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;