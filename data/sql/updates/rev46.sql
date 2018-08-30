
-- Ajustes

ALTER TABLE products ADD CONSTRAINT products_unit_fk FOREIGN KEY (idProductUnit) REFERENCES product_units(id);
ALTER TABLE products ADD CONSTRAINT products_name_uq UNIQUE (name);
ALTER TABLE products CHANGE COLUMN utility utility VARCHAR(128) NOT NULL DEFAULT '';
ALTER TABLE products CHANGE COLUMN inactive inactive TINYINT(1) NOT NULL DEFAULT '0';

-- Especificação das Categorias de Produtos

ALTER TABLE products ADD COLUMN idProductFamily INT NULL DEFAULT NULL;
ALTER TABLE products ADD COLUMN idProductGroup INT NULL DEFAULT NULL;
ALTER TABLE products ADD COLUMN idProductSubGroup INT NULL DEFAULT NULL;
ALTER TABLE products ADD COLUMN idProductSector INT NULL DEFAULT NULL;

ALTER TABLE products ADD CONSTRAINT products_family_fk FOREIGN KEY (idProductFamily) REFERENCES product_families(id);
ALTER TABLE products ADD CONSTRAINT products_group_fk FOREIGN KEY (idProductGroup) REFERENCES product_groups(id);
ALTER TABLE products ADD CONSTRAINT products_subgroup_fk FOREIGN KEY (idProductSubGroup) REFERENCES product_subgroups(id);
ALTER TABLE products ADD CONSTRAINT products_sector_fk FOREIGN KEY (idProductSector) REFERENCES product_sectores(id);

-- Atualizações

ALTER TABLE product_groups DROP INDEX product_groups_name;
ALTER TABLE product_groups ADD CONSTRAINT product_groups_name UNIQUE KEY (idProductFamily, name);

ALTER TABLE product_subgroups DROP INDEX product_subgroups_name;
ALTER TABLE product_subgroups ADD CONSTRAINT product_subgroups_name UNIQUE KEY (idProductGroup, name);

ALTER TABLE product_sectores DROP INDEX product_sectores_name;
ALTER TABLE product_sectores ADD CONSTRAINT product_sectores_name UNIQUE KEY (idProductSubGroup, name);
