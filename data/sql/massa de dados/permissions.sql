
-- TERCOM Profiles

INSERT INTO tercom_profiles (id, name, assignmentLevel) VALUES
(1, 'Administrador', 99);

-- Provider Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('provider', 'settings', 20),
('provider', 'add', 20),
('provider', 'set', 20),
('provider', 'get', 20),
('provider', 'getAll', 20),
('provider', 'list', 20),
('provider', 'search', 20),
('provider', 'setPhones', 20),
('provider', 'removeCommercial', 20),
('provider', 'removeOtherphone', 20),
('provider', 'avaiable', 20);

-- Provider Contact Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('providerContact', 'settings', 20),
('providerContact', 'add', 20),
('providerContact', 'set', 20),
('providerContact', 'setPhones', 20),
('providerContact', 'removeCommercial', 20),
('providerContact', 'removeOtherphone', 20),
('providerContact', 'removeContact', 20),
('providerContact', 'getContact', 20),
('providerContact', 'getContacts', 20);

-- Manufacturer Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('manufacturer', 'settings', 20),
('manufacturer', 'add', 20),
('manufacturer', 'set', 20),
('manufacturer', 'remove', 20),
('manufacturer', 'get', 20),
('manufacturer', 'getAll', 20),
('manufacturer', 'search', 20),
('manufacturer', 'avaiable', 20);

-- Product Unit Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productUnit', 'settings', 20),
('productUnit', 'add', 20),
('productUnit', 'set', 20),
('productUnit', 'remove', 20),
('productUnit', 'get', 20),
('productUnit', 'getAll', 20),
('productUnit', 'search', 20),
('productUnit', 'avaiable', 20);

-- TERCOM Profiles Permissions

REPLACE INTO tercom_profile_permissions (idTercomProfile, idPermission)
SELECT 1, id FROM permissions;
