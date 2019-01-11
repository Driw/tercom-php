
CREATE TABLE IF NOT EXISTS quoted_product_prices
(
	id INT AUTO_INCREMENT,
	idProduct INT NOT NULL,
	idProvider INT NOT NULL,
	idManufacturer INT NULL DEFAULT NULL,
	idProductPackage INT NOT NULL,
	idProductType INT NULL DEFAULT NULL,
	name VARCHAR(64) NULL DEFAULT NULL,
	amount SMALLINT NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	lastUpdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT quoted_product_prices_pk PRIMARY KEY (id),
	CONSTRAINT quoted_product_prices_product_fk FOREIGN KEY (idProduct) REFERENCES products(id) ON DELETE RESTRICT,
	CONSTRAINT quoted_product_prices_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE,
	CONSTRAINT quoted_product_prices_manufacture_fk FOREIGN KEY (idManufacturer) REFERENCES manufacturers(id) ON DELETE RESTRICT,
	CONSTRAINT quoted_product_prices_package_fk FOREIGN KEY (idProductPackage) REFERENCES product_packages(id) ON DELETE RESTRICT,
	CONSTRAINT quoted_product_prices_type_fk FOREIGN KEY (idProductType) REFERENCES product_types(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS quoted_order_product
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderItemProduct INT NOT NULL,
	idQuotedProductPrice INT NOT NULL,
	observations VARCHAR(128) DEFAULT NULL NULL,

	CONSTRAINT order_quotes_products_pk PRIMARY KEY (id),
	CONSTRAINT order_quotes_products_item_fk FOREIGN KEY (idOrderItemProduct) REFERENCES order_item_products(id),
	CONSTRAINT order_quotes_products_price_fk FOREIGN KEY (idQuotedProductPrice) REFERENCES quoted_product_prices(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
