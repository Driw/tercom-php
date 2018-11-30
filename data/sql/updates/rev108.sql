
-- Novas tabelas

CREATE TABLE IF NOT EXISTS tercom_profile_permissions
(
	idTercomProfile INT NOT NULL,
	idPermission INT NOT NULL,

	CONSTRAINT tercom_profile_per_pk PRIMARY KEY (idTercomProfile, idPermission),
	CONSTRAINT tercom_profile_per_tercom_fk FOREIGN KEY (idTercomProfile) REFERENCES tercom_profiles(id),
	CONSTRAINT tercom_profile_per_permission_fk FOREIGN KEY (idPermission) REFERENCES permissions(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
