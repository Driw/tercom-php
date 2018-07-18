
ALTER TABLE providers CHANGE COLUMN companyName companyName VARCHAR(72) NOT NULL;
ALTER TABLE provider_contact CHANGE COLUMN post position VARCHAR(32) NULL DEFAULT NULL;
