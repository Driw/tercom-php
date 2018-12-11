
-- Atualizações

ALTER TABLE product_units ADD CONSTRAINT product_units_name_uq UNIQUE KEY (name);
ALTER TABLE product_units ADD CONSTRAINT product_units_shortName_uq UNIQUE KEY (shortName);
