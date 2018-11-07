
-- Novas tabelas

CREATE TABLE IF NOT EXISTS customers
(
	id INT AUTO_INCREMENT,
	stateRegistry VARCHAR(16) NOT NULL,
	cnpj CHAR(14) NOT NULL,
	companyName VARCHAR(72) NOT NULL,
	fantasyName VARCHAR(48) NOT NULL,
	email VARCHAR(48) NOT NULL,
	inactive TINYINT(1) NOT NULL DEFAULT 0,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT customers_cnpj_uq UNIQUE KEY (cnpj),
	CONSTRAINT customers_fantasy_name_uq UNIQUE KEY (fantasyName),
	CONSTRAINT customers_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_phones
(
	idCustomer INT NOT NULL,
	idPhone INT NOT NULL,

	CONSTRAINT customers_phones_pk PRIMARY KEY (idCustomer, idPhone),
	CONSTRAINT customers_phones_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id) ON DELETE CASCADE,
	CONSTRAINT customers_phones_phone_fk FOREIGN KEY (idPhone) REFERENCES phones(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_addresses
(
	idCustomer INT NOT NULL,
	idAddress INT NOT NULL,

	CONSTRAINT customers_addresses_pk PRIMARY KEY (idCustomer, idAddress),
	CONSTRAINT customers_addresses_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id) ON DELETE CASCADE,
	CONSTRAINT customers_addresses_address_fk FOREIGN KEY (idAddress) REFERENCES addresses(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
