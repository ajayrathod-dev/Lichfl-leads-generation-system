-- Migration: create loan_status_history table
-- Run this in your MySQL console or phpMyAdmin. Make a DB backup first.

CREATE TABLE IF NOT EXISTS `loan_status_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `complaint_id` VARCHAR(255) NOT NULL,
  `status` TINYINT NOT NULL,
  `remarks` TEXT,
  `changed_by` VARCHAR(100),
  `changed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`complaint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example: view history for a complaint_id
-- SELECT status, remarks, changed_by, changed_at FROM loan_status_history WHERE complaint_id='...'
