
-- Novas tabelas

CREATE TABLE IF NOT EXISTS customer_employees
(
	id INT AUTO_INCREMENT,
	idCustomerProfile INT NOT NULL,
	name VARCHAR(48) NOT NULL,
	email VARCHAR(48) NOT NULL,
	password CHAR(60) NOT NULL,
	idPhone INT NULL DEFAULT NULL,
	idCellPhone INT NULL DEFAULT NULL,
	enabled TINYINT(1) NOT NULL DEFAULT 1,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT customer_employees_pk PRIMARY KEY (id),
	CONSTRAINT customer_employees_profile_fk FOREIGN KEY (idCustomerProfile) REFERENCES customer_profiles(id),
	CONSTRAINT customer_employees_phone_fk FOREIGN KEY (idPhone) REFERENCES phones(id),
	CONSTRAINT customer_employees_cellphone_fk FOREIGN KEY (idCellPhone) REFERENCES phones(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

-- Atualizações de tabelas

ALTER TABLE customer_profile_permissions ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
