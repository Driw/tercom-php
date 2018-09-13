
-- Novas tabelas

CREATE TABLE IF NOT EXISTS product_prices
(
	id INT AUTO_INCREMENT,
	idProduct INT NOT NULL,
	idProvider INT NOT NULL,
	idManufacture INT NOT NULL,
	idProductPackage INT NOT NULL,
	idProductType INT NOT NULL,
	name VARCHAR(64) DEFAULT NULL,
	amount SMALLINT NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	lastUpdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT product_prices_pk PRIMARY KEY (id),
	CONSTRAINT product_prices_product_fk FOREIGN KEY (idProduct) REFERENCES products(id) ON DELETE RESTRICT,
	CONSTRAINT product_prices_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE,
	CONSTRAINT product_prices_manufacture_fk FOREIGN KEY (idManufacture) REFERENCES manufacturers(id) ON DELETE RESTRICT,
	CONSTRAINT product_prices_package_fk FOREIGN KEY (idProductPackage) REFERENCES product_packages(id) ON DELETE RESTRICT,
	CONSTRAINT product_prices_type_fk FOREIGN KEY (idProductType) REFERENCES product_types(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
