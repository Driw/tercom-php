
-- Provider Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('provider', 'settings', 20),
('provider', 'add', 20),
('provider', 'set', 20),
('provider', 'get', 20),
('provider', 'getByProduct', 20),
('provider', 'getByService', 20),
('provider', 'getAll', 20),
('provider', 'list', 20),
('provider', 'search', 20),
('provider', 'setPhones', 20),
('provider', 'removeCommercial', 20),
('provider', 'removeOtherphone', 20),
('provider', 'avaiable', 20);

-- Provider Contact Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
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

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('manufacturer', 'settings', 20),
('manufacturer', 'add', 20),
('manufacturer', 'set', 20),
('manufacturer', 'remove', 20),
('manufacturer', 'get', 20),
('manufacturer', 'getByProduct', 20),
('manufacturer', 'getAll', 20),
('manufacturer', 'search', 20),
('manufacturer', 'avaiable', 20);

-- Product Unit Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('productUnit', 'settings', 20),
('productUnit', 'add', 20),
('productUnit', 'set', 20),
('productUnit', 'remove', 20),
('productUnit', 'get', 20),
('productUnit', 'getAll', 20),
('productUnit', 'search', 20),
('productUnit', 'avaiable', 20);

-- Product Family Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
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

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
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

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
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

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('productSector', 'settings', 20),
('productSector', 'add', 20),
('productSector', 'set', 20),
('productSector', 'setInactive', 20),
('productSector', 'get', 20),
('productSector', 'getAll', 20),
('productSector', 'search', 20),
('productSector', 'avaiable', 20);

-- Product Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('product', 'settings', 20),
('product', 'add', 20),
('product', 'set', 20),
('product', 'setInactive', 20),
('product', 'get', 20),
('product', 'getAll', 20),
('product', 'search', 20),
('product', 'avaiable', 20);

-- Product Package Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('productPackage', 'settings', 20),
('productPackage', 'add', 20),
('productPackage', 'set', 20),
('productPackage', 'remove', 20),
('productPackage', 'get', 20),
('productPackage', 'getAll', 20),
('productPackage', 'search', 20),
('productPackage', 'avaiable', 20);

-- Product Type Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('productType', 'settings', 20),
('productType', 'add', 20),
('productType', 'set', 20),
('productType', 'remove', 20),
('productType', 'get', 20),
('productType', 'getAll', 20),
('productType', 'search', 20),
('productType', 'avaiable', 20);

-- Product Price Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('productPrice', 'settings', 20),
('productPrice', 'add', 20),
('productPrice', 'set', 20),
('productPrice', 'remove', 20),
('productPrice', 'get', 20),
('productPrice', 'getAll', 20),
('productPrice', 'search', 20);

-- Service Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('service', 'settings', 20),
('service', 'add', 20),
('service', 'set', 20),
('service', 'setInactive', 20),
('service', 'get', 20),
('service', 'getAll', 20),
('service', 'search', 20),
('service', 'avaiable', 20);

-- Service Price Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('servicePrice', 'settings', 20),
('servicePrice', 'add', 20),
('servicePrice', 'set', 20),
('servicePrice', 'remove', 20),
('servicePrice', 'get', 20),
('servicePrice', 'getProvider', 20),
('servicePrice', 'getAll', 20),
('servicePrice', 'search', 20);

-- Customer Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('customer', 'settings', 20),
('customer', 'add', 20),
('customer', 'set', 20),
('customer', 'setInactive', 20),
('customer', 'get', 20),
('customer', 'getByCnpj', 20),
('customer', 'getAll', 20),
('customer', 'search', 20),
('customer', 'avaiable', 20);

-- Customer Profile Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('customerProfile', 'settings', 20),
('customerProfile', 'add', 20),
('customerProfile', 'set', 20),
('customerProfile', 'remove', 20),
('customerProfile', 'get', 20),
('customerProfile', 'customer', 20),
('customerProfile', 'actions', 20),
('customerProfile', 'getAll', 20);

-- Customer Employee Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('customerEmployee', 'settings', 20),
('customerEmployee', 'add', 20),
('customerEmployee', 'set', 20),
('customerEmployee', 'enable', 20),
('customerEmployee', 'get', 20),
('customerEmployee', 'getAll', 20),
('customerEmployee', 'getByCustomer', 20),
('customerEmployee', 'getByProfile', 20),
('customerEmployee', 'avaiable', 20);

-- Address Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('address', 'settings', 20),
('address', 'add', 20),
('address', 'set', 20),
('address', 'remove', 20),
('address', 'get', 20),
('address', 'getAll', 20);

-- Tercom Profile Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('tercomProfile', 'settings', 20),
('tercomProfile', 'add', 20),
('tercomProfile', 'set', 20),
('tercomProfile', 'remove', 20),
('tercomProfile', 'get', 20),
('tercomProfile', 'getAll', 20),
('tercomProfile', 'avaiable', 20);

-- Tercom Employee Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('tercomEmployee', 'settings', 20),
('tercomEmployee', 'add', 20),
('tercomEmployee', 'set', 20),
('tercomEmployee', 'enable', 20),
('tercomEmployee', 'get', 20),
('tercomEmployee', 'getAll', 20),
('tercomEmployee', 'getByProfile', 20),
('tercomEmployee', 'avaiable', 20);

-- Manage Permission Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('managePermissions', 'settings', 20),
('managePermissions', 'add', 20),
('managePermissions', 'set', 20),
('managePermissions', 'remove', 20),
('managePermissions', 'get', 20),
('managePermissions', 'getAll', 20);

-- Phone Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('phone', 'settings', 20),
('phone', 'add', 20),
('phone', 'set', 20),
('phone', 'remove', 20),
('phone', 'get', 20),
('phone', 'getAll', 20);

-- Order Request Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderRequest', 'settings', 20),
('orderRequest', 'add', 20),
('orderRequest', 'set', 20),
('orderRequest', 'remove', 20),
('orderRequest', 'get', 20),
('orderRequest', 'getAll', 20),
('orderRequest', 'getByCustomer', 20),
('orderRequest', 'getByTercom', 20),
('orderRequest', 'setQueued', 20),
('orderRequest', 'setQuoting', 20);

-- Order Item Product Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderItemProduct', 'settings', 20),
('orderItemProduct', 'add', 20),
('orderItemProduct', 'set', 20),
('orderItemProduct', 'remove', 20),
('orderItemProduct', 'removeAll', 20),
('orderItemProduct', 'get', 20),
('orderItemProduct', 'getAll', 20),
('orderItemProduct', 'avaiable', 20);

-- Order Item Service Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderItemService', 'settings', 20),
('orderItemService', 'add', 20),
('orderItemService', 'set', 20),
('orderItemService', 'remove', 20),
('orderItemService', 'removeAll', 20),
('orderItemService', 'get', 20),
('orderItemService', 'getAll', 20),
('orderItemService', 'avaiable', 20);

-- Order Quote Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderQuote', 'quote', 20),
('orderQuote', 'quoted', 20),
('orderQuote', 'get', 20),
('orderQuote', 'getByCustomer', 20),
('orderQuote', 'getByCustomerEmployee', 20),
('orderQuote', 'getByTercomEmployee', 20),
('orderQuote', 'getAll', 20);

-- Order Quote Product Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('quotedProductPrice', 'add', 20),
('quotedProductPrice', 'remove', 20),
('quotedProductPrice', 'removeAll', 20),
('quotedProductPrice', 'get', 20),
('quotedProductPrice', 'getAll', 20),
('quotedProductPrice', 'prices', 20);

-- Quoted Service Price Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('quotedServicePrice', 'add', 20),
('quotedServicePrice', 'remove', 20),
('quotedServicePrice', 'removeAll', 20),
('quotedServicePrice', 'get', 20),
('quotedServicePrice', 'getAll', 20),
('quotedServicePrice', 'prices', 20);

-- Order Acceptance Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderAcceptance', 'add', 20),
('orderAcceptance', 'set', 20),
('orderAcceptance', 'setAddress', 20),
('orderAcceptance', 'get', 20),
('orderAcceptance', 'getByOrderQuote', 20),
('orderAcceptance', 'getByCustomer', 20),
('orderAcceptance', 'getByCustomerEmployee', 20),
('orderAcceptance', 'getByTercomEmployee', 20);

-- Order Acceptance Product Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderAcceptanceProduct', 'add', 20),
('orderAcceptanceProduct', 'set', 20),
('orderAcceptanceProduct', 'remove', 20),
('orderAcceptanceProduct', 'removeAll', 20),
('orderAcceptanceProduct', 'get', 20),
('orderAcceptanceProduct', 'getAll', 20);

-- Order Acceptance Service Service

REPLACE INTO permissions (packet, action, assignmentLevel) VALUES
('orderAcceptanceService', 'add', 20),
('orderAcceptanceService', 'set', 20),
('orderAcceptanceService', 'remove', 20),
('orderAcceptanceService', 'removeAll', 20),
('orderAcceptanceService', 'get', 20),
('orderAcceptanceService', 'getAll', 20);

-- TERCOM Profile Permissions

REPLACE INTO tercom_profile_permissions (idTercomProfile, idPermission)
SELECT 1, id FROM permissions;

-- Customer Profile Permissions

REPLACE INTO customer_profile_permissions (idCustomerProfile, idPermission)
SELECT 1, id FROM permissions;
