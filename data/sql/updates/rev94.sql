
-- Novas tabelas

CREATE TABLE IF NOT EXISTS customer_profiles
(
	id INT AUTO_INCREMENT,
	idCustomer INT NOT NULL,
	name VARCHAR(64) NOT NULL,
	assignmentLevel TINYINT NOT NULL,

	CONSTRAINT customer_profiles_name_uq UNIQUE KEY (name),
	CONSTRAINT customer_profiles_pk PRIMARY KEY (id),
	CONSTRAINT customer_profiles_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
