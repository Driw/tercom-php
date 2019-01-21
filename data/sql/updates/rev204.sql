
-- Correções

ALTER TABLE order_acceptance_products CHANGE amount_request amountRequest SMALLINT NOT NULL;

-- Novas Tabelas

CREATE TABLE order_acceptance_services
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderAcceptance INT NOT NULL,
	idQuotedServicePrice INT NOT NULL,
	idService INT NOT NULL,
	idProvider INT NOT NULL,
	name VARCHAR(64) NULL DEFAULT NULL,
	additionalDescription VARCHAR(256) NULL DEFAULT NULL,
	amountRequest SMALLINT NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	subprice DECIMAL(10,2) NOT NULL,
	observations TINYTEXT NULL DEFAULT NULL,
	lastUpdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT order_acceptance_services_pk PRIMARY KEY (id),
	CONSTRAINT order_acceptance_services_uk UNIQUE KEY (idOrderAcceptance, idQuotedServicePrice),
	CONSTRAINT order_acceptance_services_acceptance_fk FOREIGN KEY (idOrderAcceptance) REFERENCES order_acceptances(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_services_quoted_fk FOREIGN KEY (idQuotedServicePrice) REFERENCES quoted_service_prices(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_services_service_fk FOREIGN KEY (idService) REFERENCES services(id) ON DELETE RESTRICT,
	CONSTRAINT order_acceptance_services_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
