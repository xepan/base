ALTER TABLE `custom_form_submission` ADD COLUMN `related_type` varchar(255) DEFAULT NULL;
ALTER TABLE `custom_form_submission` ADD COLUMN `related_id` int(11) DEFAULT 0;