
-- Atualização de Tabelas

ALTER TABLE order_item_products CHANGE COLUMN batterPrice betterPrice TINYINT(1) NOT NULL;
ALTER TABLE order_item_services CHANGE COLUMN batterPrice betterPrice TINYINT(1) NOT NULL;
