
CREATE TABLE IF NOT EXISTS phones
(
	id INT AUTO_INCREMENT,
	ddd TINYINT(2) NOT NULL,
	number VARCHAR(9) NOT NULL,
	type ENUM('residential', 'cellphone', 'commercial'),

	CONSTRAINT phones_pk PRIMARY KEY(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS providers
(
	id INT AUTO_INCREMENT,
	cnpj CHAR(14) NOT NULL,
	companyName VARCHAR(72) NOT NULL,
	fantasyName VARCHAR(48) NOT NULL,
	spokesman VARCHAR(48) NULL DEFAULT NULL,
	site VARCHAR(64) NULL DEFAULT NULL,
	commercial INT NULL DEFAULT NULL,
	otherphone INT NULL DEFAULT NULL,
	inactive ENUM('no', 'yes') NOT NULL DEFAULT 'no',

	CONSTRAINT providers_pk PRIMARY KEY(id),
	CONSTRAINT providers_cnpj UNIQUE KEY(cnpj),
	CONSTRAINT providers_fk_commercial FOREIGN KEY (commercial) REFERENCES phones(id) ON DELETE SET NULL,
	CONSTRAINT providers_fk_otherphone FOREIGN KEY (otherphone) REFERENCES phones(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS provider_contact
(
	id INT AUTO_INCREMENT,
    name VARCHAR(48) NOT NULL,
    position VARCHAR(32) NULL DEFAULT NULL,
    email VARCHAR(48) NULL DEFAULT NULL,
	commercial INT NULL DEFAULT NULL,
	otherphone INT NULL DEFAULT NULL,

	CONSTRAINT provider_contact_pk PRIMARY KEY(id),
	CONSTRAINT provider_contact_fk_commercial FOREIGN KEY (commercial) REFERENCES phones(id) ON DELETE SET NULL,
	CONSTRAINT provider_contact_fk_otherphone FOREIGN KEY (otherphone) REFERENCES phones(id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS provider_contacts
(
	idProvider INT NOT NULL,
	idProviderContact INT NOT NULL,
    priority SMALLINT NOT NULL DEFAULT '99',

	CONSTRAINT provider_contacts_pk PRIMARY KEY (idProvider, idProviderContact),
    CONSTRAINT provider_contacts_fk_provider FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE,
    CONSTRAINT provider_contacts_fk_contact FOREIGN KEY (idProviderContact) REFERENCES provider_contact(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS manufacturers
(
	id INT AUTO_INCREMENT,
	fantasyName VARCHAR(48) NOT NULL,

	CONSTRAINT manufacturers_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
