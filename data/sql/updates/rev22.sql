
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

	CONSTRAINT product_groups_name UNIQUE KEY (name),
	CONSTRAINT product_groups_pk PRIMARY KEY (id),
	CONSTRAINT product_groups_fk FOREIGN KEY (idProductFamily) REFERENCES product_families(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_subgroups
(
	id INT AUTO_INCREMENT,
	idProductGroup INT NOT NULL,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_subgroups_name UNIQUE KEY (name),
	CONSTRAINT product_subgroups_pk PRIMARY KEY (id),
	CONSTRAINT product_subgroups_fk FOREIGN KEY (idProductGroup) REFERENCES product_groups(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS product_sectores
(
	id INT AUTO_INCREMENT,
	idProductSubgroup INT NOT NULL,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_sectores_name UNIQUE KEY (name),
	CONSTRAINT product_sectores_pk PRIMARY KEY (id),
	CONSTRAINT product_sectores_fk FOREIGN KEY (idProductSubgroup) REFERENCES product_subgroups(id) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
