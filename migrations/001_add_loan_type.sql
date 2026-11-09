-- Migration: add loan_type column to complaint table
-- Run this in your MySQL console or phpMyAdmin. Make a DB backup first.

ALTER TABLE `complaint`
  ADD COLUMN `loan_type` VARCHAR(100) DEFAULT NULL;

-- Heuristic migration: if complaint_title contains a prefix like "Type - Title", extract prefix into loan_type and strip it from the title.
-- WARNING: This is a best-effort migration and may misclassify titles. Review results before committing.

-- 1) Populate loan_type as text before the first ' - ' for rows that don't already have loan_type
UPDATE `complaint`
SET `loan_type` = TRIM(SUBSTRING_INDEX(`complaint_title`, ' - ', 1))
WHERE `loan_type` IS NULL AND `complaint_title` LIKE '% - %';

-- 2) Remove the extracted prefix from complaint_title when loan_type was set by the previous query
UPDATE `complaint`
SET `complaint_title` = TRIM(SUBSTRING_INDEX(`complaint_title`, ' - ', -1))
WHERE `loan_type` IS NOT NULL AND `complaint_title` LIKE '% - %';

-- After running, inspect rows with NULL loan_type and decide if manual cleanup is needed.
SELECT id, complaint_id, complaint_title, loan_type FROM `complaint` LIMIT 100;
