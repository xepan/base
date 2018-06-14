ALTER TABLE `communication` ADD COLUMN `related_contact_id` Int ;
ALTER TABLE `communication` ADD INDEX (`related_contact_id`);