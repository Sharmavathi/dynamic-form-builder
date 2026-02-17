CREATE TABLE IF NOT EXISTS `conditional_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT NULL,
  `trigger_field_id` int(11) DEFAULT NULL,
  `operator` varchar(10) DEFAULT NULL,
  `trigger_value` varchar(255) DEFAULT NULL,
  `target_field_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`form_id`) REFERENCES `forms`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`trigger_field_id`) REFERENCES `form_fields`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`target_field_id`) REFERENCES `form_fields`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
