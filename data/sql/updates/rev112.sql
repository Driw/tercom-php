
-- Novas tabelas

CREATE TABLE IF NOT EXISTS logins
(
	id INT AUTO_INCREMENT,
	token VARCHAR(32) NOT NULL,
	logout TINYINT(1) NOT NULL DEFAULT 0,
	ipAddress VARCHAR(15) NOT NULL,
	browser VARCHAR(128) NOT NULL,
	expiration DATETIME NOT NULL,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT login_token_uq UNIQUE KEY (token),
	CONSTRAINT login_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS logins_customer
(
	idLogin INT AUTO_INCREMENT,
	idCustomerEmployee INT NOT NULL,

	CONSTRAINT login_customer_pk PRIMARY KEY (idLogin),
	CONSTRAINT login_customer_login_pk FOREIGN KEY (idLogin) REFERENCES logins(id),
	CONSTRAINT login_customer_employee_fk FOREIGN KEY (idCustomerEmployee) REFERENCES customer_employees(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS logins_tercom
(
	idLogin INT AUTO_INCREMENT,
	idTercomEmployee INT NOT NULL,

	CONSTRAINT login_tercom_pk PRIMARY KEY (idLogin),
	CONSTRAINT login_tercom_login_pk FOREIGN KEY (idLogin) REFERENCES logins(id),
	CONSTRAINT login_tercom_employee_fk FOREIGN KEY (idTercomEmployee) REFERENCES tercom_employees(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
