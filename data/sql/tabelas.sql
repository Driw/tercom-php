
CREATE TABLE IF NOT EXISTS phones
(
	id INT AUTO_INCREMENT,
	ddd TINYINT(2) NOT NULL,
	number VARCHAR(9) NOT NULL,
	type ENUM('residential', 'cellphone', 'commercial', 'whatsapp'),

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
	inactive TINYINT(1) NOT NULL DEFAULT '0',

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

CREATE TABLE IF NOT EXISTS product_families
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_families_name UNIQUE KEY (name),
	CONSTRAINT product_families_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_groups
(
	id INT AUTO_INCREMENT,
	idProductFamily INT NOT NULL,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_groups_name UNIQUE KEY (idProductFamily, name),
	CONSTRAINT product_groups_pk PRIMARY KEY (id),
	CONSTRAINT product_groups_fk FOREIGN KEY (idProductFamily) REFERENCES product_families(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_subgroups
(
	id INT AUTO_INCREMENT,
	idProductGroup INT NOT NULL,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_subgroups_name UNIQUE KEY (idProductGroup, name),
	CONSTRAINT product_subgroups_pk PRIMARY KEY (id),
	CONSTRAINT product_subgroups_fk FOREIGN KEY (idProductGroup) REFERENCES product_groups(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_sectores
(
	id INT AUTO_INCREMENT,
	idProductSubgroup INT NOT NULL,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_sectores_name UNIQUE KEY (idProductSubgroup, name),
	CONSTRAINT product_sectores_pk PRIMARY KEY (id),
	CONSTRAINT product_sectores_fk FOREIGN KEY (idProductSubgroup) REFERENCES product_subgroups(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_units
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,
	shortName VARCHAR(6) NOT NULL,

	CONSTRAINT product_units_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_types
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_types_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_packages
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_packages_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS products
(
	id INT AUTO_INCREMENT,
	name VARCHAR(48) NOT NULL,
	description VARCHAR(128) NOT NULL,
	utility VARCHAR(128) NOT NULL DEFAULT '',
	inactive TINYINT(1) NOT NULL DEFAULT '0',
	idProductUnit INT NOT NULL,
	idProductFamily INT NULL DEFAULT NULL,
	idProductGroup INT NULL DEFAULT NULL,
	idProductSubGroup INT NULL DEFAULT NULL,
	idProductSector INT NULL DEFAULT NULL,

	CONSTRAINT products_pk PRIMARY KEY (id),
	CONSTRAINT products_unit_fk FOREIGN KEY (idProductUnit) REFERENCES product_units(id),
	CONSTRAINT products_family_fk FOREIGN KEY (idProductFamily) REFERENCES product_families(id),
	CONSTRAINT products_group_fk FOREIGN KEY (idProductGroup) REFERENCES product_groups(id),
	CONSTRAINT products_subgroup_fk FOREIGN KEY (idProductSubGroup) REFERENCES product_subgroups(id),
	CONSTRAINT products_sector_fk FOREIGN KEY (idProductSector) REFERENCES product_sectores(id),
	CONSTRAINT products_name_uq UNIQUE (name)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

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

CREATE TABLE IF NOT EXISTS services
(
	id INT AUTO_INCREMENT,
	name VARCHAR(48) NOT NULL,
	description VARCHAR(256) NOT NULL,
	tags VARCHAR(64) NOT NULL,
	inactive TINYINT(1) DEFAULT 0,

	CONSTRAINT services_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS service_prices
(
	id INT AUTO_INCREMENT,
	idService INT NOT NULL,
	idProvider INT NOT NULL,
	name VARCHAR(48) NULL DEFAULT NULL,
	additionalDescription VARCHAR(256) NULL DEFAULT NULL,
	price DECIMAL(10, 2) NOT NULL,

	CONSTRAINT service_prices_pk PRIMARY KEY (id),
	CONSTRAINT service_prices_product_fk FOREIGN KEY (idService) REFERENCES services(id) ON DELETE RESTRICT,
	CONSTRAINT service_prices_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
