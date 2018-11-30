
-- Novas tabelas

CREATE TABLE IF NOT EXISTS tercom_employees
(
	id INT AUTO_INCREMENT,
	idTercomProfile INT NOT NULL,
	cpf CHAR(11) NOT NULL,
	name VARCHAR(48) NOT NULL,
	email VARCHAR(48) NOT NULL,
	password CHAR(60) NOT NULL,
	idPhone INT NULL DEFAULT NULL,
	idCellPhone INT NULL DEFAULT NULL,
	enabled TINYINT(1) NOT NULL DEFAULT 1,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT tercom_employees_cpf_uq UNIQUE KEY (cpf),
	CONSTRAINT tercom_employees_pk PRIMARY KEY (id),
	CONSTRAINT tercom_employees_profile_fk FOREIGN KEY (idTercomProfile) REFERENCES tercom_profiles(id),
	CONSTRAINT tercom_employees_phone_fk FOREIGN KEY (idPhone) REFERENCES phones(id),
	CONSTRAINT tercom_employees_cellphone_fk FOREIGN KEY (idCellPhone) REFERENCES phones(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
