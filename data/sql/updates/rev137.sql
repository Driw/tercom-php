
-- Novas Tabelas

CREATE TABLE IF NOT EXISTS order_requests
(
	id INT NOT NULL AUTO_INCREMENT,
	idCustomerEmployee INT NOT NULL,
	idTercomEmployee INT NULL DEFAULT NULL,
	status TINYINT(1) NOT NULL DEFAULT 0,
    statusMessage VARCHAR(64) NULL DEFAULT NULL,
	budget DECIMAL(10,2),
	expiration DATETIME NULL DEFAULT NULL,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT order_request_pk PRIMARY KEY (id),
	CONSTRAINT order_request_cutomer_employee_fk FOREIGN KEY (idCustomerEmployee) REFERENCES customer_employees(id),
	CONSTRAINT order_request_tercom_employee_fk FOREIGN KEY (idTercomEmployee) REFERENCES tercom_employees(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
