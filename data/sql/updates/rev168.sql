
-- Novas Tabelas

CREATE TABLE IF NOT EXISTS order_item_products
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderRequest INT NOT NULL,
	idProduct INT NOT NULL,
	idProvider INT DEFAULT NULL NULL,
	idManufacturer INT DEFAULT NULL NULL,
	batterPrice TINYINT(1) NOT NULL,
	observations VARCHAR(128) DEFAULT NULL NULL,

	CONSTRAINT order_item_products_pk PRIMARY KEY (id),
	CONSTRAINT order_item_products_uk UNIQUE KEY (idOrderRequest, idProduct),
	CONSTRAINT order_item_products_product_fk FOREIGN KEY (idProduct) REFERENCES products(id),
	CONSTRAINT order_item_products_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id),
	CONSTRAINT order_item_products_manufacturer_fk FOREIGN KEY (idManufacturer) REFERENCES manufacturers(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS order_item_services
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderRequest INT NOT NULL,
	idService INT NOT NULL,
	idProvider INT DEFAULT NULL NULL,
	batterPrice TINYINT(1) NOT NULL,
	observations VARCHAR(128) DEFAULT NULL NULL,

	CONSTRAINT order_item_services_pk PRIMARY KEY (id),
	CONSTRAINT order_item_services_uk UNIQUE KEY (idOrderRequest, idService),
	CONSTRAINT order_item_services_service_fk FOREIGN KEY (idService) REFERENCES services(id),
	CONSTRAINT order_item_services_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
