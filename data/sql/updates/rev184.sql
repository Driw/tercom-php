
-- Atualização de Tabela

ALTER TABLE service_prices ADD COLUMN lastUpdate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL AFTER price;
