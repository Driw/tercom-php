
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

CREATE TABLE IF NOT EXISTS product_category_types
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_category_types_name_uq UNIQUE KEY (name),
	CONSTRAINT product_category_types_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_categories
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_categories_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_category_relationships
(
	idCategoryParent INT NOT NULL,
	idCategory INT NOT NULL,
	idCategoryType INT NOT NULL,

	CONSTRAINT product_categories_relantionship_pk PRIMARY KEY (idCategory, idCategoryType),
	CONSTRAINT product_categories_relantionship_cat_fk FOREIGN KEY (idCategoryParent) REFERENCES product_categories(id),
	CONSTRAINT product_categories_relantionship_rel_fk FOREIGN KEY (idCategory) REFERENCES product_categories(id),
	CONSTRAINT product_categories_relantionship_type_fk FOREIGN KEY (idCategoryType) REFERENCES product_category_types(id)
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
	idProductCategory INT NULL DEFAULT NULL,

	CONSTRAINT products_pk PRIMARY KEY (id),
	CONSTRAINT products_unit_fk FOREIGN KEY (idProductUnit) REFERENCES product_units(id),
	CONSTRAINT products_name_uq UNIQUE (name),
	CONSTRAINT products_category_fk FOREIGN KEY (idProductCategory) REFERENCES product_categories(id)
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

CREATE TABLE IF NOT EXISTS customers
(
	id INT AUTO_INCREMENT,
	stateRegistry VARCHAR(16) NOT NULL,
	cnpj CHAR(14) NOT NULL,
	companyName VARCHAR(72) NOT NULL,
	fantasyName VARCHAR(48) NOT NULL,
	email VARCHAR(48) NOT NULL,
	inactive TINYINT(1) NOT NULL DEFAULT 0,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT customers_cnpj_uq UNIQUE KEY (cnpj),
	CONSTRAINT customers_fantasy_name_uq UNIQUE KEY (fantasyName),
	CONSTRAINT customers_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_phones
(
	idCustomer INT NOT NULL,
	idPhone INT NOT NULL,

	CONSTRAINT customers_phones_pk PRIMARY KEY (idCustomer, idPhone),
	CONSTRAINT customers_phones_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id) ON DELETE CASCADE,
	CONSTRAINT customers_phones_phone_fk FOREIGN KEY (idPhone) REFERENCES phones(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_addresses
(
	idCustomer INT NOT NULL,
	idAddress INT NOT NULL,

	CONSTRAINT customers_addresses_pk PRIMARY KEY (idCustomer, idAddress),
	CONSTRAINT customers_addresses_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id) ON DELETE CASCADE,
	CONSTRAINT customers_addresses_address_fk FOREIGN KEY (idAddress) REFERENCES addresses(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS permissions
(
	id INT AUTO_INCREMENT,
	packet VARCHAR(32) NOT NULL,
	action VARCHAR(32) NOT NULL,
	assignmentLevel TINYINT NOT NULL,

	CONSTRAINT permissions_uq UNIQUE KEY (packet, action),
	CONSTRAINT permissions_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_profiles
(
	id INT AUTO_INCREMENT,
	idCustomer INT NOT NULL,
	name VARCHAR(64) NOT NULL,
	assignmentLevel TINYINT NOT NULL,

	CONSTRAINT customer_profiles_name_uq UNIQUE KEY (name),
	CONSTRAINT customer_profiles_pk PRIMARY KEY (id),
	CONSTRAINT customer_profiles_customer_fk FOREIGN KEY (idCustomer) REFERENCES customers(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_profile_permissions
(
	idCustomerProfile INT NOT NULL,
	idPermission INT NOT NULL,

	CONSTRAINT customer_profile_per_pk PRIMARY KEY (idCustomerProfile, idPermission),
	CONSTRAINT customer_profile_per_customer_fk FOREIGN KEY (idCustomerProfile) REFERENCES customer_profiles(id),
	CONSTRAINT customer_profile_per_permission_fk FOREIGN KEY (idPermission) REFERENCES permissions(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS customer_employees
(
	id INT AUTO_INCREMENT,
	idCustomerProfile INT NOT NULL,
	name VARCHAR(48) NOT NULL,
	email VARCHAR(48) NOT NULL,
	password CHAR(60) NOT NULL,
	idPhone INT NULL DEFAULT NULL,
	idCellPhone INT NULL DEFAULT NULL,
	enabled TINYINT(1) NOT NULL DEFAULT 1,
	register DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT customer_employees_pk PRIMARY KEY (id),
	CONSTRAINT customer_employees_profile_fk FOREIGN KEY (idCustomerProfile) REFERENCES customer_profiles(id),
	CONSTRAINT customer_employees_phone_fk FOREIGN KEY (idPhone) REFERENCES phones(id),
	CONSTRAINT customer_employees_cellphone_fk FOREIGN KEY (idCellPhone) REFERENCES phones(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
