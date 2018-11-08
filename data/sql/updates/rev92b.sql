
-- Novas tabelas

CREATE TABLE IF NOT EXISTS permissions
(
	id INT AUTO_INCREMENT,
	packet VARCHAR(32) NOT NULL,
	action VARCHAR(32) NOT NULL,
	assignmentLevel TINYINT NOT NULL,

	CONSTRAINT permissions_uq UNIQUE KEY (packet, action),
	CONSTRAINT permissions_pk PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
