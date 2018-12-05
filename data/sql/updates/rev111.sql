
-- Atualização de Tabelas

ALTER TABLE customer_employees ADD CONSTRAINT customer_employees_email_uq UNIQUE KEY (email);
ALTER TABLE tercom_employees ADD CONSTRAINT tercom_employees_email_uq UNIQUE KEY (email);
