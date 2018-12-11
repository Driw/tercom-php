
-- Atualizações

ALTER TABLE products CHANGE COLUMN description description TINYTEXT NOT NULL;
ALTER TABLE products CHANGE COLUMN utility utility TINYTEXT NOT NULL DEFAULT '';
