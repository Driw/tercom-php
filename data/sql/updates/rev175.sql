
CREATE TABLE IF NOT EXISTS order_quotes
(
	id INT NOT NULL AUTO_INCREMENT,
	idOrderRequest INT NOT NULL,
	status TINYINT(1) DEFAULT 0 NOT NULL,
	register DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,

	CONSTRAINT order_quotes_pk PRIMARY KEY (id),
	CONSTRAINT order_quotes_request_fk FOREIGN KEY (idOrderRequest) REFERENCES order_requests(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
