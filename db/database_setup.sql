/*
filename: database_setup.sql
author: Ondřej Patejdl
date: 16.10.2025
description: This file creates entities and their relations used throughout the app
*/
SET time_zone = '+00:00';
START TRANSACTION;

-- -------------------------------------------------------------------
-- DROP TABLES
-- -------------------------------------------------------------------
DROP TABLE IF EXISTS opatejdl_review;
DROP TABLE IF EXISTS opatejdl_product;
DROP TABLE IF EXISTS opatejdl_user;
DROP TABLE IF EXISTS opatejdl_category;
DROP TABLE IF EXISTS opatejdl_role;

-- -------------------------------------------------------------------
-- CREATE TABLES
-- -------------------------------------------------------------------

-- 1. Role
CREATE TABLE IF NOT EXISTS opatejdl_role (
    id_role             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(30) NOT NULL,
    priority            INT UNSIGNED NOT NULL,
    UNIQUE KEY uq_role_name (name)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- 2. User
CREATE TABLE IF NOT EXISTS opatejdl_user (
    id_user             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fk_id_role          INT UNSIGNED NOT NULL DEFAULT 4,
    username            VARCHAR(50) NOT NULL,
    email               VARCHAR(50) NOT NULL,
    password            VARCHAR(250) NOT NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_username (username),
    UNIQUE KEY uq_user_email (email),
    KEY idx_user_role (fk_id_role),
    KEY idx_user_created_at (created_at)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- 3. PRODUCT CATEGORY
CREATE TABLE IF NOT EXISTS opatejdl_category (
  id_category           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                  VARCHAR(60) NOT NULL,
  UNIQUE KEY uq_category_name (name)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- 4. PRODUCT
CREATE TABLE IF NOT EXISTS opatejdl_product (
    id_product          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fk_id_category      INT UNSIGNED NULL,
    name                VARCHAR(120) NOT NULL,
    price               INT UNSIGNED NOT NULL DEFAULT 0,
    photo_url           VARCHAR(255) NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_product_name (name),
    KEY idx_product_category (fk_id_category),
    KEY idx_product_created_at (created_at),
    CONSTRAINT chk_product_price CHECK (price >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- 5. Review
CREATE TABLE IF NOT EXISTS opatejdl_review (
    id_review           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fk_id_user          INT UNSIGNED NOT NULL,
    fk_id_product       INT UNSIGNED NOT NULL,
    rating              TINYINT UNSIGNED NOT NULL,
    description         VARCHAR(1000) NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    publicity           TINYINT UNSIGNED NOT NUlL,
    UNIQUE KEY uq_review_user_product (fk_id_user, fk_id_product),
    KEY idx_review_user (fk_id_user),
    KEY idx_review_product (fk_id_product),
    KEY idx_review_product_created_at (fk_id_product, created_at),
    CONSTRAINT chk_review_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- -------------------------------------------------------------------
-- FK Constraints
-- -------------------------------------------------------------------

-- User and Role
ALTER TABLE opatejdl_user
  ADD CONSTRAINT fk_user_role
    FOREIGN KEY (fk_id_role)
    REFERENCES opatejdl_role(id_role)
    ON UPDATE CASCADE
    ON DELETE RESTRICT;

-- Product and Category
ALTER TABLE opatejdl_product
  ADD CONSTRAINT fk_product_category
    FOREIGN KEY (fk_id_category)
    REFERENCES opatejdl_category(id_category)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

-- Review, Product and User
ALTER TABLE opatejdl_review
  ADD CONSTRAINT fk_review_user
    FOREIGN KEY (fk_id_user)
    REFERENCES opatejdl_user(id_user)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  ADD CONSTRAINT fk_review_product
    FOREIGN KEY (fk_id_product)
    REFERENCES opatejdl_product(id_product)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

-- -------------------------------------------------------------------
-- INSERT DATA
-- -------------------------------------------------------------------

INSERT INTO opatejdl_role (id_role, name, priority) VALUES
 (1, 'Super Admin', 20),
 (2, 'Admin', 15),
 (3, 'Manager', 10),
 (4, 'Consumer', 5);

INSERT INTO opatejdl_category (id_category, name) VALUES
 (1, 'Polévka'),
 (2, 'Hlavní Jídlo'),
 (3, 'Dezert'),
 (4, 'Nápoj');

INSERT INTO opatejdl_user (id_user, fk_id_role, username, email, password) VALUES
    (10, 4, 'karel',       'karel@example.com',       '$2y$10$hBvRHv8bCKQZ0wJCvFdLtuoWQ6pNgxeEceEEWqh/qTk91fVOVCGTy'),
    (11, 3, 'iva',         'iva@example.com',         '$2y$10$eA3yXKzbRgLrUQQB562FQu3yUo7D4jQdrR654VulKchbZtUV5iS6y'),
    (12, 2, 'admin',       'admin@example.com',       '$2y$10$ngA2cuCkscoa1/qSTjUdueKn88.pFyFpuhUcc9EYrdhSrh..DQfmC'),
    (13, 1, 'opatejdl',    'opatejdl@students.zcu.cz', '$2y$10$wz/Sf5SqfEj5KH0euE573uJo4EKhd02yts0reoG64QXqHuhx9qfAW');

INSERT INTO opatejdl_product (id_product, fk_id_category, name, price, photo_url) VALUES
 (1, 1, 'Česnečka',                95, 'data/img/cesnecka.jpg'),
 (2, 2, 'Svíčková na smetaně',    189, 'data/img/svickova.jpg'),
 (3, 3, 'Jablečný štrúdl',         79, 'data/img/strudl.jpg'),
 (4, 4, 'Domácí limonáda',         55, 'data/img/limonada.jpg');

INSERT INTO opatejdl_review (id_review, fk_id_user, fk_id_product, rating, description, publicity) VALUES
 (1, 10, 1, 5, 'Výborná polévka, poctivý česnek.', 1),
 (2, 11, 2, 4, 'Klasika, omáčka super, knedlík by mohl být lepší.', 1),
 (3, 11, 3, 5, 'Teplý a křupavý, přesně jak má být.', 0),
 (4, 10, 4, 3, 'Osvěžující, nicméně nic extra.', 1);

COMMIT;
