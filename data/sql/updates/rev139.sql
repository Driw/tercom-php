
-- Atualizações

ALTER TABLE customer_profile_permissions DROP FOREIGN KEY customer_profile_per_customer_fk;
ALTER TABLE customer_profile_permissions ADD CONSTRAINT customer_profile_per_customer_fk FOREIGN KEY (idCustomerProfile) REFERENCES customer_profiles(id) ON DELETE CASCADE;

ALTER TABLE tercom_profile_permissions DROP FOREIGN KEY tercom_profile_per_tercom_fk;
ALTER TABLE tercom_profile_permissions ADD CONSTRAINT tercom_profile_per_tercom_fk FOREIGN KEY (idTercomProfile) REFERENCES tercom_profiles(id) ON DELETE CASCADE;
