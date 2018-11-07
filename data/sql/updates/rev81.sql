
-- Novas tabelas

CREATE TABLE IF NOT EXISTS addresses
(
	id INT AUTO_INCREMENT,
	state VARCHAR(2) NOT NULL,
	city VARCHAR(48) NOT NULL,
	cep CHAR(8) NOT NULL,
	neighborhood VARCHAR(32) NOT NULL,
	street VARCHAR(32) NOT NULL,
	number INT(5) NOT NULL,
	complement VARCHAR(24) NULL DEFAULT NULL,

	CONSTRAINT addresses_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
