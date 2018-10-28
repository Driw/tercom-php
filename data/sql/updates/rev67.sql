
-- Atualizações

ALTER TABLE providers CHANGE COLUMN type ENUM('residential', 'cellphone', 'commercial', 'whatsapp');
ALTER TABLE providers ADD COLUMN inactive TINYINT(1) DEFAULT 1;
