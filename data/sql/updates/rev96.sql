
-- Novas tabelas

CREATE TABLE IF NOT EXISTS product_category_types
(
	id INT AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,

	CONSTRAINT product_category_types_name_uq UNIQUE KEY (name),
	CONSTRAINT product_category_types_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;

INSERT INTO product_category_types(name) VALUES
('Família'),
('Grupo'),
('Subgrupo'),
('Setor');

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

-- Atualização de tabelas

ALTER TABLE products DROP FOREIGN KEY products_family_fk;
ALTER TABLE products DROP FOREIGN KEY products_group_fk;
ALTER TABLE products DROP FOREIGN KEY products_subgroup_fk;
ALTER TABLE products DROP FOREIGN KEY products_sector_fk;

ALTER TABLE products DROP INDEX products_family_fk;
ALTER TABLE products DROP INDEX products_group_fk;
ALTER TABLE products DROP INDEX products_subgroup_fk;
ALTER TABLE products DROP INDEX products_sector_fk;

ALTER TABLE products DROP COLUMN idProductFamily;
ALTER TABLE products DROP COLUMN idProductGroup;
ALTER TABLE products DROP COLUMN idProductSubGroup;
ALTER TABLE products DROP COLUMN idProductSector;

ALTER TABLE products ADD COLUMN idProductCategory INT NULL DEFAULT NULL AFTER idProductUnit;
ALTER TABLE products ADD CONSTRAINT products_category_fk FOREIGN KEY (idProductCategory) REFERENCES product_categories(id);

-- Exclusão de tabelas

DROP TABLE product_sectores;
DROP TABLE product_subgroups;
DROP TABLE product_groups;
DROP TABLE product_families;
