
-- Atualizações

ALTER TABLE product_packages ADD CONSTRAINT product_packages_name_uk UNIQUE KEY (name);
ALTER TABLE product_prices CHANGE COLUMN idManufacture idManufacturer INT NULL DEFAULT NULL;
ALTER TABLE product_prices CHANGE COLUMN idProductType idProductType INT NULL DEFAULT NULL;
ALTER TABLE product_prices CHANGE COLUMN name name VARCHAR(64) NULL DEFAULT NULL;
