
CREATE TABLE IF NOT EXISTS product_units
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,
	initials VARCHAR(6) NOT NULL,

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
	idProductUnit INT NOT NULL,
	name VARCHAR(48) NOT NULL,
	description VARCHAR(128) NOT NULL,
	utility VARCHAR(128) NULL DEFAULT NULL,
	inactive ENUM('no', 'yes') DEFAULT 'no',

	CONSTRAINT products_pk PRIMARY KEY (id),
	CONSTRAINT product_units_fk FOREIGN KEY (unit) REFERENCES product_units(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_values
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

	CONSTRAINT product_values_pk PRIMARY KEY (id),
	CONSTRAINT product_values_product_fk FOREIGN KEY (idProduct) REFERENCES products(id) ON DELETE RESTRICT,
	CONSTRAINT product_values_provider_fk FOREIGN KEY (idProvider) REFERENCES providers(id) ON DELETE CASCADE,
	CONSTRAINT product_values_manufacture_fk FOREIGN KEY (idManufacture) REFERENCES manufacturers(id) ON DELETE RESTRICT,
	CONSTRAINT product_values_package_fk FOREIGN KEY (idProductPackage) REFERENCES product_packages(id) ON DELETE RESTRICT,
	CONSTRAINT product_values_type_fk FOREIGN KEY (idProductType) REFERENCES product_types(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
