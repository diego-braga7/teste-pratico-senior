-- init.sql

-- Cria o banco apenas se não existir
CREATE DATABASE IF NOT EXISTS `quiz_app`
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
USE `quiz_app`;

-- Cria tabela de migrações (opcional, para controle futuro)
CREATE TABLE IF NOT EXISTS `schema_migrations` (
  `version` VARCHAR(100) PRIMARY KEY,
  `applied_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Usuários
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Leads
CREATE TABLE IF NOT EXISTS `leads` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (`email`)
) ENGINE=InnoDB;

-- Quizzes
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `created_by` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Perguntas
CREATE TABLE IF NOT EXISTS `questions` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quiz_id` BIGINT UNSIGNED NOT NULL,
  `question_text` TEXT NOT NULL,
  `response_type` ENUM('multiple_choice','text','boolean') NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Alternativas
CREATE TABLE IF NOT EXISTS `alternatives` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `question_id` BIGINT UNSIGNED NOT NULL,
  `option_text` VARCHAR(255) NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Respostas dos leads
CREATE TABLE IF NOT EXISTS `lead_responses` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` BIGINT UNSIGNED NOT NULL,
  `quiz_id` BIGINT UNSIGNED NOT NULL,
  `question_id` BIGINT UNSIGNED NOT NULL,
  `alternative_id` BIGINT UNSIGNED NULL,
  `answer_text` TEXT NULL,
  `responded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`)       REFERENCES `leads`(`id`)      ON DELETE CASCADE,
  FOREIGN KEY (`quiz_id`)       REFERENCES `quizzes`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`question_id`)   REFERENCES `questions`(`id`)  ON DELETE CASCADE,
  FOREIGN KEY (`alternative_id`)REFERENCES `alternatives`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;
