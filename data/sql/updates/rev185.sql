
CREATE TABLE IF NOT EXISTS quoted_service_prices
(
	id INT AUTO_INCREMENT,
	idService INT NOT NULL,
	idProvider INT NOT NULL,
	name VARCHAR(48) NULL DEFAULT NULL,
	additionalDescription VARCHAR(256) NULL DEFAULT NULL,
	price DECIMAL(10, 2) NOT NULL,
	lastUpdate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,

	CONSTRAINT quoted_service_prices_pk PRIMARY KEY (id),
	CONSTRAINT quoted_service_prices_product_fk FOREIGN KEY (idService) REFERENCES services(id) ON DELETE RESTRICT,
	CONSTRAINT quoted_service_prices_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS quoted_order_services
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderItemService INT NOT NULL,
	idQuotedServicePrice INT NOT NULL,
	observations VARCHAR(128) DEFAULT NULL NULL,

	CONSTRAINT quoted_order_services_pk PRIMARY KEY (id),
	CONSTRAINT quoted_order_services_item_fk FOREIGN KEY (idOrderItemService) REFERENCES order_item_services(id),
	CONSTRAINT quoted_order_services_price_fk FOREIGN KEY (idQuotedServicePrice) REFERENCES quoted_service_prices(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
