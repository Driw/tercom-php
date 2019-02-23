
-- Novas Tabelas

CREATE TABLE product_customer
(
	idProduct INT NOT NULL,
	idCustomer INT NOT NULL,
	idCustom VARCHAR(32) NOT NULL,

	CONSTRAINT UNIQUE KEY (idCustomer, idCustom),
	CONSTRAINT PRIMARY KEY (idProduct, idCustomer),
	CONSTRAINT product_customer_product_fk FOREIGN KEY (idProduct) REFERENCES products(id) ON DELETE RESTRICT,
	CONSTRAINT product_customer_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE service_customer
(
	idService INT NOT NULL,
	idCustomer INT NOT NULL,
	idCustom VARCHAR(32) NOT NULL,

	CONSTRAINT UNIQUE KEY (idCustomer, idCustom),
	CONSTRAINT PRIMARY KEY (idService, idCustomer),
	CONSTRAINT service_customer_service_fk FOREIGN KEY (idService) REFERENCES services(id) ON DELETE RESTRICT,
	CONSTRAINT service_customer_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
