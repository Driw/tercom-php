
-- Novas tabelas

CREATE TABLE IF NOT EXISTS customer_profile_permissions
(
	idCustomerProfile INT NOT NULL,
	idPermission INT NOT NULL,

	CONSTRAINT customer_profile_per_pk PRIMARY KEY (idCustomerProfile, idPermission),
	CONSTRAINT customer_profile_per_customer_fk FOREIGN KEY (idCustomerProfile) REFERENCES customer_profiles(id),
	CONSTRAINT customer_profile_per_permission_fk FOREIGN KEY (idPermission) REFERENCES permissions(id)
);
