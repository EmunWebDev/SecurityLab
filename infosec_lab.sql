SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

--CONSTRAINTS WHEN DROPING TABLE
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `users`;

--CREATE TABLE USERS--
CREATE TABLE `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,   
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--INSERTING VALUES ON USERS TABLE WITH PASSWORD AS HASHED--
--UNLIKE IN LARAVEL I DO SEEDER AND DO HASH FUNCTION--
--HERE I JUST CONVERT IT TO BYCRYPT HASHED--
INSERT INTO `users` (`username`, `password`) VALUES
('admin', '$2y$12$I9Y1GYR6oCONEGcAJXLaTOtO.BtULx4R.TfB3NFzV4k/f3gXk4MCa');

--CREATE TABLE COURSES SEPARATE FROM BEFORE WITH STUDENT--
--NORMALIZED IT--
CREATE TABLE `courses` (
  `id`                 INT(11)      NOT NULL AUTO_INCREMENT,
  `course_name`        VARCHAR(100) NOT NULL UNIQUE,
  `course_description` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--INSERTING VALUES IN COURSES TABLE--
INSERT INTO `courses` (`course_name`, `course_description`) VALUES
('BSIT',  'Bachelor of Science in Information Technology'),
('BSCS',  'Bachelor of Science in Computer Science'),
('BSIS',  'Bachelor of Science in Information Systems'),
('BSCPE', 'Bachelor of Science in Computer Engineering');

--CREATE STUDENTS TABLE--
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
