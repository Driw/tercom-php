
CREATE TABLE IF NOT EXISTS services
(
	id INT AUTO_INCREMENT,
	name VARCHAR(48) NOT NULL,
	description VARCHAR(256) NOT NULL,
	tags VARCHAR(64) NOT NULL,
	inactive TINYINT(1) DEFAULT 0,

	CONSTRAINT services_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS service_timelimits
(
	id INT AUTO_INCREMENT,
	duration BIGINT NOT NULL COMMENT 'milliseconds',
	type TINYINT NOT NULL,

	CONSTRAINT services_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS service_values
(
	id INT AUTO_INCREMENT,
	idService INT NOT NULL,
	idProvider INT NOT NULL,
	name VARCHAR(48) NULL DEFAULT NULL,
	additionalDescription VARCHAR(256) NULL DEFAULT NULL,
	price DECIMAL(10, 2) NOT NULL,
	repetitions TINYINT NOT NULL,
	duration BIGINT NOT NULL COMMENT 'milliseconds',
	timeLimit INT NOT NULL,

	CONSTRAINT service_values_pk PRIMARY KEY (id),
	CONSTRAINT service_values_product_fk FOREIGN KEY (idService) REFERENCES services(id) ON DELETE RESTRICT,
	CONSTRAINT service_values_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE,
	CONSTRAINT service_timelimit_fk FOREIGN KEY (timeLimit) REFERENCES service_timelimits(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
