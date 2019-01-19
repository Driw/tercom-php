
CREATE TABLE order_acceptances
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderQuote INT NOT NULL,
	idCustomerEmployee INT NOT NULL,
	idTercomEmployee INT NOT NULL,
	idAddress INT NOT NULL,
	status INT NOT NULL DEFAULT 0,
	observations TINYTEXT NULL DEFAULT NULL,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT order_acceptances_pk PRIMARY KEY (id),
	CONSTRAINT order_acceptances_uk UNIQUE KEY (idOrderQuote),
	CONSTRAINT order_acceptances_order_fk FOREIGN KEY (idOrderQuote) REFERENCES order_quotes(id),
	CONSTRAINT order_acceptances_customer_fk FOREIGN KEY (idCustomerEmployee) REFERENCES customer_employees(id),
	CONSTRAINT order_acceptances_tercom_fk FOREIGN KEY (idTercomEmployee) REFERENCES tercom_employees(id),
	CONSTRAINT order_acceptances_address_fk FOREIGN KEY (idAddress) REFERENCES addresses(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
