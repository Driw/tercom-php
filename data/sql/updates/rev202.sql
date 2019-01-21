
CREATE TABLE order_acceptance_products
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderAcceptance INT NOT NULL,
	idQuotedProductPrice INT NOT NULL,
	idProduct INT NOT NULL,
	idProvider INT NOT NULL,
	idManufacturer INT NULL DEFAULT NULL,
	idProductPackage INT NOT NULL,
	idProductType INT NULL DEFAULT NULL,
	name VARCHAR(64) NULL DEFAULT NULL,
	amount SMALLINT NOT NULL,
	amount_request SMALLINT NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	subprice DECIMAL(10,2) NOT NULL,
	observations TINYTEXT NULL DEFAULT NULL,
	lastUpdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT order_acceptance_products_pk PRIMARY KEY (id),
	CONSTRAINT order_acceptance_products_uk UNIQUE KEY (idOrderAcceptance, idQuotedProductPrice),
	CONSTRAINT order_acceptance_products_acceptance_fk FOREIGN KEY (idOrderAcceptance) REFERENCES order_acceptances(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_products_quoted_fk FOREIGN KEY (idQuotedProductPrice) REFERENCES quoted_product_prices(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_products_product_fk FOREIGN KEY (idProduct) REFERENCES products(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_products_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE,
	CONSTRAINT order_acceptance_products_manufacture_fk FOREIGN KEY (idManufacturer) REFERENCES manufacturers(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_products_package_fk FOREIGN KEY (idProductPackage) REFERENCES product_packages(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_products_type_fk FOREIGN KEY (idProductType) REFERENCES product_types(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
