
-- Correções

ALTER TABLE providers CHANGE COLUMN inactive inactive ENUM('no', 'yes') NOT NULL DEFAULT 'no';

-- Implementações

CREATE TABLE IF NOT EXISTS manufacturers
(
	id INT AUTO_INCREMENT,
	fantasyName VARCHAR(48) NOT NULL,

	CONSTRAINT manufacturers_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
