-- Freelance Marketplace Database
-- Group 22 | FAST University Peshawar | BCS-6A

CREATE DATABASE IF NOT EXISTS `freelance` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `freelance`;

DROP TABLE IF EXISTS `reset_password_token`;
DROP TABLE IF EXISTS `log`;
DROP TABLE IF EXISTS `job_payment_dispatch`;
DROP TABLE IF EXISTS `job_payment`;
DROP TABLE IF EXISTS `job_rating`;
DROP TABLE IF EXISTS `job_proposal`;
DROP TABLE IF EXISTS `job_skill`;
DROP TABLE IF EXISTS `job`;
DROP TABLE IF EXISTS `freelancer_skill`;
DROP TABLE IF EXISTS `freelancer`;
DROP TABLE IF EXISTS `client`;
DROP TABLE IF EXISTS `skill`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
    `id`           INT(11)      NOT NULL AUTO_INCREMENT,
    `email`        VARCHAR(100) NOT NULL,
    `phone`        VARCHAR(13)  NOT NULL,
    `password`     VARCHAR(255) NOT NULL,
    `first_name`   VARCHAR(255) NOT NULL,
    `middle_name`  VARCHAR(255)          DEFAULT NULL,
    `last_name`    VARCHAR(255) NOT NULL,
    `image`        VARCHAR(255)          DEFAULT NULL,
    `country`      VARCHAR(255)          DEFAULT 'Pakistan',
    `county`       VARCHAR(255)          DEFAULT NULL,
    `city`         VARCHAR(255)          DEFAULT NULL,
    `is_admin`     TINYINT(1)   NOT NULL DEFAULT 0,
    `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
    `time_created` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_email` (`email`),
    UNIQUE KEY `uq_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `client` (
    `id`           INT(11)                      NOT NULL AUTO_INCREMENT,
    `user_id`      INT(11)                      NOT NULL,
    `national_id`  VARCHAR(255)                          DEFAULT NULL,
    `image`        VARCHAR(255)                          DEFAULT NULL,
    `title`        VARCHAR(255)                 NOT NULL,
    `description`  TEXT                         NOT NULL,
    `type`         ENUM('individual','company') NOT NULL DEFAULT 'individual',
    `time_created` DATETIME                     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_active`    TINYINT(1)                   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_client_user` (`user_id`),
    CONSTRAINT `fk_client_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `freelancer` (
    `id`                  INT(11)      NOT NULL AUTO_INCREMENT,
    `user_id`             INT(11)      NOT NULL,
    `national_id`         VARCHAR(255)          DEFAULT NULL,
    `title`               VARCHAR(255) NOT NULL,
    `description`         TEXT         NOT NULL,
    `years_of_experience` INT(11)      NOT NULL DEFAULT 0,
    `time_created`        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_active`           INT(11)      NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_freelancer_user` (`user_id`),
    CONSTRAINT `fk_freelancer_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `skill` (
    `id`   INT(11)      NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_skill` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `freelancer_skill` (
    `id`            INT(11) NOT NULL AUTO_INCREMENT,
    `freelancer_id` INT(11) NOT NULL,
    `skill_id`      INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_fs` (`freelancer_id`,`skill_id`),
    CONSTRAINT `fk_fs_freelancer` FOREIGN KEY (`freelancer_id`) REFERENCES `freelancer`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_fs_skill`      FOREIGN KEY (`skill_id`)      REFERENCES `skill`(`id`)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job` (
    `id`                             INT(11)      NOT NULL AUTO_INCREMENT,
    `client_id`                      INT(11)      NOT NULL,
    `title`                          VARCHAR(255) NOT NULL,
    `description`                    TEXT         NOT NULL,
    `image`                          VARCHAR(255)          DEFAULT NULL,
    `pay_rate_per_hour`              FLOAT        NOT NULL DEFAULT 0,
    `expected_duration_in_hours`     FLOAT        NOT NULL DEFAULT 0,
    `receive_job_proposals_deadline` DATETIME     NOT NULL,
    `time_created`                   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_active`                      TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_job_client` FOREIGN KEY (`client_id`) REFERENCES `client`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_skill` (
    `id`       INT(11) NOT NULL AUTO_INCREMENT,
    `job_id`   INT(11) NOT NULL,
    `skill_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_job_skill` (`job_id`,`skill_id`),
    CONSTRAINT `fk_js_job`   FOREIGN KEY (`job_id`)   REFERENCES `job`(`id`),
    CONSTRAINT `fk_js_skill` FOREIGN KEY (`skill_id`) REFERENCES `skill`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_proposal` (
    `id`                     INT(11)      NOT NULL AUTO_INCREMENT,
    `status`                 ENUM('sent','accepted','completed successfully','rejected','withdrawn','completed unsuccessfully','work submitted','job expired before work submission') NOT NULL DEFAULT 'sent',
    `title`                  VARCHAR(255) NOT NULL,
    `description`            TEXT         NOT NULL,
    `job_id`                 INT(11)      NOT NULL,
    `freelancer_id`          INT(11)      NOT NULL,
    `submission_description` TEXT                  DEFAULT NULL,
    `submission_attachment`  VARCHAR(255)          DEFAULT NULL,
    `client_comment`         TEXT                  DEFAULT NULL,
    `time_work_starts`       DATETIME              DEFAULT NULL,
    `time_work_ends`         DATETIME              DEFAULT NULL,
    `time_created`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_active`              TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_proposal` (`job_id`,`freelancer_id`),
    CONSTRAINT `fk_jp_job`        FOREIGN KEY (`job_id`)       REFERENCES `job`(`id`)        ON DELETE CASCADE,
    CONSTRAINT `fk_jp_freelancer` FOREIGN KEY (`freelancer_id`) REFERENCES `freelancer`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_rating` (
    `id`      INT(11)                    NOT NULL AUTO_INCREMENT,
    `job_id`  INT(11)                    NOT NULL,
    `type`    ENUM('freelancer','client') NOT NULL,
    `rating`  INT(11)                    NOT NULL,
    `comment` TEXT                                DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_rating` (`job_id`,`type`),
    CONSTRAINT `fk_jr_job` FOREIGN KEY (`job_id`) REFERENCES `job`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_payment` (
    `id`                           INT(11)     NOT NULL AUTO_INCREMENT,
    `job_id`                       INT(11)     NOT NULL,
    `phone_number`                 VARCHAR(12) NOT NULL,
    `amount`                       FLOAT       NOT NULL,
    `is_payment_successful`        TINYINT(1)  NOT NULL DEFAULT 0,
    `response_merchant_request_id` VARCHAR(255) NOT NULL DEFAULT '',
    `response_checkout_request_id` VARCHAR(255) NOT NULL DEFAULT '',
    `response_response_code`       VARCHAR(255)          DEFAULT NULL,
    `callback_result_code`         VARCHAR(255)          DEFAULT NULL,
    `callback_result_desc`         VARCHAR(255)          DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_jpm_job` FOREIGN KEY (`job_id`) REFERENCES `job`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_payment_dispatch` (
    `id`                                  INT(11)     NOT NULL AUTO_INCREMENT,
    `job_payment_id`                      INT(11)     NOT NULL,
    `is_refund`                           TINYINT(1)  NOT NULL DEFAULT 0,
    `phone_number`                        VARCHAR(12) NOT NULL,
    `amount`                              FLOAT       NOT NULL,
    `is_dispatch_successful`              TINYINT(1)  NOT NULL DEFAULT 0,
    `response_conversation_id`            VARCHAR(255) NOT NULL DEFAULT '',
    `response_originator_conversation_id` VARCHAR(255) NOT NULL DEFAULT '',
    `response_response_code`              VARCHAR(255)          DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_jpd_payment` FOREIGN KEY (`job_payment_id`) REFERENCES `job_payment`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `log` (
    `id`           INT(11)  NOT NULL AUTO_INCREMENT,
    `user_id`      INT(11)           DEFAULT NULL,
    `type`         ENUM('Log In','Log Out','Create Freelancer','Create Client','General Log') DEFAULT 'General Log',
    `action`       TEXT     NOT NULL,
    `ip_address`   VARCHAR(15)       DEFAULT NULL,
    `time_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reset_password_token` (
    `id`           INT(11)      NOT NULL AUTO_INCREMENT,
    `user_id`      INT(11)      NOT NULL,
    `token`        VARCHAR(255) NOT NULL,
    `time_expires` DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_rpt_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample skills
INSERT INTO `skill` (`name`) VALUES
('PHP'),('JavaScript'),('Python'),('Java'),('React'),
('MySQL'),('Laravel'),('Node.js'),('WordPress'),('UI/UX Design'),
('Graphic Design'),('Content Writing'),('SEO'),('Android Development'),('iOS Development');

-- Admin user (password: password)
-- Note: Hash generated using PHP password_hash('password', PASSWORD_DEFAULT)
INSERT INTO `user` (`email`,`phone`,`password`,`first_name`,`last_name`,`is_admin`) VALUES
('admin@freelance.com','03001234567','$2y$10$TKh8H1.PfQ0A0pVT9q8CdOfFBWJLbcHe4lRLw7IKGa8gXTpKqQ0yq','Admin','User',1);
