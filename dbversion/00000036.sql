ALTER TABLE `post` MODIFY COLUMN `salary_template_id`  int(11) NULL DEFAULT NULL AFTER `out_time`;
ALTER TABLE `post` MODIFY COLUMN `leave_template_id`  int(11) NULL DEFAULT NULL AFTER `salary_template_id`;