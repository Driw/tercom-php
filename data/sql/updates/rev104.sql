
-- Novas tabelas

CREATE TABLE IF NOT EXISTS tercom_profiles
(
	id INT AUTO_INCREMENT,
	name VARCHAR(64) NOT NULL,
	assignmentLevel TINYINT NOT NULL,

	CONSTRAINT customer_profiles_name_uq UNIQUE KEY (name),
	CONSTRAINT customer_profiles_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
