
-- Correção UNIQUE KEY para customer_profiles

ALTER TABLE customer_profiles DROP INDEX customer_profiles_name_uq;
ALTER TABLE customer_profiles ADD CONSTRAINT customer_profiles_name_uk UNIQUE KEY (idCustomer, name);
