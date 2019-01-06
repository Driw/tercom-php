
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

-- Product Family Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productFamily', 'settings', 20),
('productFamily', 'add', 20),
('productFamily', 'set', 20),
('productFamily', 'remove', 20),
('productFamily', 'get', 20),
('productFamily', 'getAll', 20),
('productFamily', 'getCategories', 20),
('productFamily', 'getAllFamilies', 20),
('productFamily', 'search', 20),
('productFamily', 'avaiable', 20),
('productFamily', 'has', 20);

-- Product Group Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productGroup', 'settings', 20),
('productGroup', 'add', 20),
('productGroup', 'set', 20),
('productGroup', 'remove', 20),
('productGroup', 'get', 20),
('productGroup', 'getCategories', 20),
('productGroup', 'search', 20),
('productGroup', 'avaiable', 20),
('productGroup', 'has', 20);

-- Product Subgroup Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productSubgroup', 'settings', 20),
('productSubgroup', 'add', 20),
('productSubgroup', 'set', 20),
('productSubgroup', 'remove', 20),
('productSubgroup', 'get', 20),
('productSubgroup', 'getCategories', 20),
('productSubgroup', 'search', 20),
('productSubgroup', 'avaiable', 20),
('productSubgroup', 'has', 20);

-- Product Sector Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productSector', 'settings', 20),
('productSector', 'add', 20),
('productSector', 'set', 20),
('productSector', 'setInactive', 20),
('productSector', 'get', 20),
('productSector', 'getAll', 20),
('productSector', 'search', 20),
('productSector', 'avaiable', 20);

-- Product Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('product', 'settings', 20),
('product', 'add', 20),
('product', 'set', 20),
('product', 'setInactive', 20),
('product', 'get', 20),
('product', 'getAll', 20),
('product', 'search', 20),
('product', 'avaiable', 20);

-- Product Package Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productPackage', 'settings', 20),
('productPackage', 'add', 20),
('productPackage', 'set', 20),
('productPackage', 'remove', 20),
('productPackage', 'get', 20),
('productPackage', 'getAll', 20),
('productPackage', 'search', 20),
('productPackage', 'avaiable', 20);

-- Product Type Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productType', 'settings', 20),
('productType', 'add', 20),
('productType', 'set', 20),
('productType', 'remove', 20),
('productType', 'get', 20),
('productType', 'getAll', 20),
('productType', 'search', 20),
('productType', 'avaiable', 20);

-- Product Price Service

INSERT INTO permissions (packet, action, assignmentLevel) VALUES
('productPrice', 'settings', 20),
('productPrice', 'add', 20),
('productPrice', 'set', 20),
('productPrice', 'remove', 20),
('productPrice', 'get', 20),
('productPrice', 'getAll', 20)
('productPrice', 'search', 20);

-- TERCOM Profiles Permissions

REPLACE INTO tercom_profile_permissions (idTercomProfile, idPermission)
SELECT 1, id FROM permissions;
