
-- Cliente

REPLACE INTO customers (id, stateRegistry, cnpj, companyName, fantasyName, email, inactive) VALUES
(1, '000000000', '00000000000000', 'A Test Customer Company', 'A Test Customer', 'test@test.com.br', false);

-- Perfis de Cliente

REPLACE INTO customer_profiles (id, name, assignmentLevel) VALUES
(1, 'Administrador', 99);

-- Perfis TERCOM

REPLACE INTO tercom_profiles (id, name, assignmentLevel) VALUES
(1, 'Administrador', 99);

-- Tipos de Produtos

REPLACE INTO product_types (id, name) VALUES
(1, 'Tipo de Produto 1'),
(2, 'Tipo de Produto 2'),
(3, 'Tipo de Produto 3'),
(4, 'Tipo de Produto 4'),
(5, 'Tipo de Produto 5'),
(6, 'Tipo de Produto 6'),
(7, 'Tipo de Produto 7'),
(8, 'Tipo de Produto 8'),
(9, 'Tipo de Produto 9');

-- Categorias de Produto

REPLACE INTO product_categories (id, name) VALUES
( 1, 'Família 1'),
( 2, 'Família 2'),
( 3, 'Grupo 1.1'),
( 4, 'Grupo 1.2'),
( 5, 'Grupo 2.1'),
( 6, 'Grupo 2.2'),
( 7, 'Subgrupo 1.1.1'),
( 8, 'Subgrupo 1.1.2'),
( 9, 'Subgrupo 1.2.1'),
(10, 'Subgrupo 1.2.2'),
(11, 'Subgrupo 2.1.1'),
(12, 'Subgrupo 2.1.2'),
(13, 'Subgrupo 2.2.1'),
(14, 'Subgrupo 2.2.2'),
(15, 'Setor 1.1.1.1'),
(16, 'Setor 1.1.1.2'),
(17, 'Setor 1.1.2.1'),
(18, 'Setor 1.1.2.2'),
(19, 'Setor 1.2.1.1'),
(20, 'Setor 1.2.1.2'),
(21, 'Setor 1.2.2.1'),
(22, 'Setor 1.2.2.2'),
(23, 'Setor 2.1.1.1'),
(24, 'Setor 2.1.1.2'),
(25, 'Setor 2.1.2.1'),
(26, 'Setor 2.1.2.2'),
(27, 'Setor 2.2.1.1'),
(28, 'Setor 2.2.1.2'),
(29, 'Setor 2.2.2.1'),
(30, 'Setor 2.2.2.2');

-- Relação das Categorias de Produto

REPLACE INTO product_category_relationships (idCategoryParent, idCategory, idCategoryType) VALUES
( 1,  3, 2),
( 1,  4, 2),
( 2,  5, 2),
( 2,  6, 2),
( 3,  7, 3),
( 3,  8, 3),
( 4,  9, 3),
( 4, 10, 3),
( 5, 11, 3),
( 5, 12, 3),
( 6, 13, 3),
( 6, 14, 3),
( 7, 15, 4),
( 7, 16, 4),
( 8, 17, 4),
( 8, 18, 4),
( 9, 19, 4),
( 9, 20, 4),
(10, 21, 4),
(10, 22, 4),
(11, 23, 4),
(11, 24, 4),
(12, 25, 4),
(12, 26, 4),
(13, 27, 4),
(13, 28, 4),
(14, 29, 4),
(14, 30, 4);

-- Pacotes de Produto

REPLACE INTO product_packages (id, name) VALUES
(1, 'Embalagem de Produto 1'),
(2, 'Embalagem de Produto 2'),
(3, 'Embalagem de Produto 3'),
(4, 'Embalagem de Produto 4'),
(5, 'Embalagem de Produto 5'),
(6, 'Embalagem de Produto 6'),
(7, 'Embalagem de Produto 7'),
(8, 'Embalagem de Produto 8'),
(9, 'Embalagem de Produto 9');

-- Embalagens de Produto

REPLACE INTO product_units (id, name, shortName) VALUES
(1, 'Unidade de Produto 1', 'AB'),
(2, 'Unidade de Produto 2', 'CD'),
(3, 'Unidade de Produto 3', 'EF'),
(4, 'Unidade de Produto 4', 'GH'),
(5, 'Unidade de Produto 5', 'IJ'),
(6, 'Unidade de Produto 6', 'KL'),
(7, 'Unidade de Produto 7', 'MN'),
(8, 'Unidade de Produto 8', 'OP'),
(9, 'Unidade de Produto 9', 'RS');

-- Produtos

REPLACE INTO products (id, name, description, utility, inactive, idProductUnit, idProductCategory) VALUES
( 1, 'Nome do Produto  1', 'Descrição do Produto  1', 'Utilidade do Produto  1', true, 1,  1),
( 2, 'Nome do Produto  2', 'Descrição do Produto  2', 'Utilidade do Produto  2', true, 2,  2),
( 3, 'Nome do Produto  3', 'Descrição do Produto  3', 'Utilidade do Produto  3', true, 3,  3),
( 4, 'Nome do Produto  4', 'Descrição do Produto  4', 'Utilidade do Produto  4', true, 4,  4),
( 5, 'Nome do Produto  5', 'Descrição do Produto  5', 'Utilidade do Produto  5', true, 5,  5),
( 6, 'Nome do Produto  6', 'Descrição do Produto  6', 'Utilidade do Produto  6', true, 6,  6),
( 7, 'Nome do Produto  7', 'Descrição do Produto  7', 'Utilidade do Produto  7', true, 7,  7),
( 8, 'Nome do Produto  8', 'Descrição do Produto  8', 'Utilidade do Produto  8', true, 8,  8),
( 9, 'Nome do Produto  9', 'Descrição do Produto  9', 'Utilidade do Produto  9', true, 9,  9),
(10, 'Nome do Produto 10', 'Descrição do Produto 10', 'Utilidade do Produto 10', true, 1, 10),
(11, 'Nome do Produto 11', 'Descrição do Produto 11', 'Utilidade do Produto 11', true, 2, 11),
(12, 'Nome do Produto 12', 'Descrição do Produto 12', 'Utilidade do Produto 12', true, 3, 12),
(13, 'Nome do Produto 13', 'Descrição do Produto 13', 'Utilidade do Produto 13', true, 4, 13),
(14, 'Nome do Produto 14', 'Descrição do Produto 14', 'Utilidade do Produto 14', true, 5, 14),
(15, 'Nome do Produto 15', 'Descrição do Produto 15', 'Utilidade do Produto 15', true, 6, 15),
(16, 'Nome do Produto 16', 'Descrição do Produto 16', 'Utilidade do Produto 16', true, 7, 16),
(17, 'Nome do Produto 17', 'Descrição do Produto 17', 'Utilidade do Produto 17', true, 8, 17),
(18, 'Nome do Produto 18', 'Descrição do Produto 18', 'Utilidade do Produto 18', true, 9, 18),
(19, 'Nome do Produto 19', 'Descrição do Produto 19', 'Utilidade do Produto 19', true, 1, 19),
(20, 'Nome do Produto 20', 'Descrição do Produto 20', 'Utilidade do Produto 20', true, 2, 20),
(21, 'Nome do Produto 21', 'Descrição do Produto 21', 'Utilidade do Produto 21', true, 3, 21),
(22, 'Nome do Produto 22', 'Descrição do Produto 22', 'Utilidade do Produto 22', true, 4, 22),
(23, 'Nome do Produto 23', 'Descrição do Produto 23', 'Utilidade do Produto 23', true, 5, 23),
(24, 'Nome do Produto 24', 'Descrição do Produto 24', 'Utilidade do Produto 24', true, 6, 24),
(25, 'Nome do Produto 25', 'Descrição do Produto 25', 'Utilidade do Produto 25', true, 7, 25),
(26, 'Nome do Produto 26', 'Descrição do Produto 26', 'Utilidade do Produto 26', true, 8, 26),
(27, 'Nome do Produto 27', 'Descrição do Produto 27', 'Utilidade do Produto 27', true, 9, 27),
(28, 'Nome do Produto 28', 'Descrição do Produto 28', 'Utilidade do Produto 28', true, 1, 28),
(29, 'Nome do Produto 29', 'Descrição do Produto 29', 'Utilidade do Produto 29', true, 2, 29),
(30, 'Nome do Produto 30', 'Descrição do Produto 30', 'Utilidade do Produto 30', true, 3, 30),
(31, 'Nome do Produto 31', 'Descrição do Produto 31', 'Utilidade do Produto 31', true, 4,  1),
(32, 'Nome do Produto 32', 'Descrição do Produto 32', 'Utilidade do Produto 32', true, 5,  2),
(33, 'Nome do Produto 33', 'Descrição do Produto 33', 'Utilidade do Produto 33', true, 6,  3),
(34, 'Nome do Produto 34', 'Descrição do Produto 34', 'Utilidade do Produto 34', true, 7,  4),
(35, 'Nome do Produto 35', 'Descrição do Produto 35', 'Utilidade do Produto 35', true, 8,  5),
(36, 'Nome do Produto 36', 'Descrição do Produto 36', 'Utilidade do Produto 36', true, 9,  6),
(37, 'Nome do Produto 37', 'Descrição do Produto 37', 'Utilidade do Produto 37', true, 1,  7),
(38, 'Nome do Produto 38', 'Descrição do Produto 38', 'Utilidade do Produto 38', true, 2,  8),
(39, 'Nome do Produto 39', 'Descrição do Produto 39', 'Utilidade do Produto 39', true, 3,  9),
(40, 'Nome do Produto 40', 'Descrição do Produto 40', 'Utilidade do Produto 40', true, 4, 10),
(41, 'Nome do Produto 41', 'Descrição do Produto 41', 'Utilidade do Produto 41', true, 5, 11),
(42, 'Nome do Produto 42', 'Descrição do Produto 42', 'Utilidade do Produto 42', true, 6, 12),
(43, 'Nome do Produto 43', 'Descrição do Produto 43', 'Utilidade do Produto 43', true, 7, 13),
(44, 'Nome do Produto 44', 'Descrição do Produto 44', 'Utilidade do Produto 44', true, 8, 14),
(45, 'Nome do Produto 45', 'Descrição do Produto 45', 'Utilidade do Produto 45', true, 9, 15),
(46, 'Nome do Produto 46', 'Descrição do Produto 46', 'Utilidade do Produto 46', true, 1, 16),
(47, 'Nome do Produto 47', 'Descrição do Produto 47', 'Utilidade do Produto 47', true, 2, 17),
(48, 'Nome do Produto 48', 'Descrição do Produto 48', 'Utilidade do Produto 48', true, 3, 18),
(49, 'Nome do Produto 49', 'Descrição do Produto 49', 'Utilidade do Produto 49', true, 4, 19),
(50, 'Nome do Produto 50', 'Descrição do Produto 50', 'Utilidade do Produto 50', true, 5, 20),
(51, 'Nome do Produto 51', 'Descrição do Produto 51', 'Utilidade do Produto 51', true, 6, 21),
(52, 'Nome do Produto 52', 'Descrição do Produto 52', 'Utilidade do Produto 52', true, 7, 22),
(53, 'Nome do Produto 53', 'Descrição do Produto 53', 'Utilidade do Produto 53', true, 8, 23),
(54, 'Nome do Produto 54', 'Descrição do Produto 54', 'Utilidade do Produto 54', true, 9, 24),
(55, 'Nome do Produto 55', 'Descrição do Produto 55', 'Utilidade do Produto 55', true, 1, 25),
(56, 'Nome do Produto 56', 'Descrição do Produto 56', 'Utilidade do Produto 56', true, 2, 26),
(57, 'Nome do Produto 57', 'Descrição do Produto 57', 'Utilidade do Produto 57', true, 3, 27),
(58, 'Nome do Produto 58', 'Descrição do Produto 58', 'Utilidade do Produto 58', true, 4, 28),
(59, 'Nome do Produto 59', 'Descrição do Produto 59', 'Utilidade do Produto 59', true, 5, 29),
(60, 'Nome do Produto 60', 'Descrição do Produto 60', 'Utilidade do Produto 60', true, 6, 30),
(61, 'Nome do Produto 61', 'Descrição do Produto 61', 'Utilidade do Produto 61', false, 7,  1),
(62, 'Nome do Produto 62', 'Descrição do Produto 62', 'Utilidade do Produto 62', false, 8,  2),
(63, 'Nome do Produto 63', 'Descrição do Produto 63', 'Utilidade do Produto 63', false, 9,  3),
(64, 'Nome do Produto 64', 'Descrição do Produto 64', 'Utilidade do Produto 64', false, 1,  4),
(65, 'Nome do Produto 65', 'Descrição do Produto 65', 'Utilidade do Produto 65', false, 2,  5),
(66, 'Nome do Produto 66', 'Descrição do Produto 66', 'Utilidade do Produto 66', false, 3,  6),
(67, 'Nome do Produto 67', 'Descrição do Produto 67', 'Utilidade do Produto 67', false, 4,  7),
(68, 'Nome do Produto 68', 'Descrição do Produto 68', 'Utilidade do Produto 68', false, 5,  8),
(69, 'Nome do Produto 69', 'Descrição do Produto 69', 'Utilidade do Produto 69', false, 6,  9),
(70, 'Nome do Produto 70', 'Descrição do Produto 70', 'Utilidade do Produto 70', false, 7, 10),
(71, 'Nome do Produto 71', 'Descrição do Produto 71', 'Utilidade do Produto 71', false, 8, 11),
(72, 'Nome do Produto 72', 'Descrição do Produto 72', 'Utilidade do Produto 72', false, 9, 12),
(73, 'Nome do Produto 73', 'Descrição do Produto 73', 'Utilidade do Produto 73', false, 1, 13),
(74, 'Nome do Produto 74', 'Descrição do Produto 74', 'Utilidade do Produto 74', false, 2, 14),
(75, 'Nome do Produto 75', 'Descrição do Produto 75', 'Utilidade do Produto 75', false, 3, 15),
(76, 'Nome do Produto 76', 'Descrição do Produto 76', 'Utilidade do Produto 76', false, 4, 16),
(77, 'Nome do Produto 77', 'Descrição do Produto 77', 'Utilidade do Produto 77', false, 5, 17),
(78, 'Nome do Produto 78', 'Descrição do Produto 78', 'Utilidade do Produto 78', false, 6, 18),
(79, 'Nome do Produto 79', 'Descrição do Produto 79', 'Utilidade do Produto 79', false, 7, 19),
(80, 'Nome do Produto 80', 'Descrição do Produto 80', 'Utilidade do Produto 80', false, 8, 20);

-- Preços de Produto

REPLACE INTO product_prices (id, idProduct, idProvider, idManufacturer, idProductPackage, idProductType, name, amount, price, lastUpdate) VALUES
(  1,  1,  1,   1,  1,  1, 'Nome do Preço de Produto   1',   0, 897.05, '2019-01-11 23:38:17'),
(  2,  1,  2,   2,  2,  2, 'Nome do Preço de Produto   2',   0, 282.62, '2019-01-11 23:38:17'),
(  3,  1,  3,   3,  3,  3, 'Nome do Preço de Produto   3',   0, 803.45, '2019-01-11 23:38:17'),
(  4,  1,  4,   4,  4,  4, 'Nome do Preço de Produto   4',   1, 834.30, '2019-01-11 23:38:17'),
(  5,  1,  5,   5,  5,  5, 'Nome do Preço de Produto   5',   1, 753.03, '2019-01-11 23:38:17'),
(  6,  2,  1,   6,  6,  6, 'Nome do Preço de Produto   6',   0, 720.20, '2019-01-11 23:38:17'),
(  7,  2,  2,   7,  7,  7, 'Nome do Preço de Produto   7',   3, 794.21, '2019-01-11 23:38:17'),
(  8,  2,  3,   8,  8,  8, 'Nome do Preço de Produto   8',   3, 507.30, '2019-01-11 23:38:17'),
(  9,  2,  4,   9,  9,  9, 'Nome do Preço de Produto   9',   3, 544.63, '2019-01-11 23:38:17'),
( 10,  2,  5,  10,  1,  1, 'Nome do Preço de Produto  10',   2, 667.41, '2019-01-11 23:38:17'),
( 11,  3,  1,  11,  2,  2, 'Nome do Preço de Produto  11',   1, 242.17, '2019-01-11 23:38:17'),
( 12,  3,  2,  12,  3,  3, 'Nome do Preço de Produto  12',   1, 613.97, '2019-01-11 23:38:17'),
( 13,  3,  3,  13,  4,  4, 'Nome do Preço de Produto  13',   6, 489.26, '2019-01-11 23:38:17'),
( 14,  3,  4,  14,  5,  5, 'Nome do Preço de Produto  14',   6, 506.63, '2019-01-11 23:38:17'),
( 15,  3,  5,  15,  6,  6, 'Nome do Preço de Produto  15',   6, 628.67, '2019-01-11 23:38:17'),
( 16,  4,  1,  16,  7,  7, 'Nome do Preço de Produto  16',   6, 459.80, '2019-01-11 23:38:17'),
( 17,  4,  2,  17,  8,  8, 'Nome do Preço de Produto  17',   6, 514.95, '2019-01-11 23:38:17'),
( 18,  4,  3,  18,  9,  9, 'Nome do Preço de Produto  18',   6, 751.65, '2019-01-11 23:38:17'),
( 19,  4,  4,  19,  1,  1, 'Nome do Preço de Produto  19',   5, 705.54, '2019-01-11 23:38:17'),
( 20,  4,  5,  20,  2,  2, 'Nome do Preço de Produto  20',   5, 206.55, '2019-01-11 23:38:17'),
( 21,  5,  1,  21,  3,  3, 'Nome do Preço de Produto  21',   2, 492.19, '2019-01-11 23:38:17'),
( 22,  5,  2,  22,  4,  4, 'Nome do Preço de Produto  22',   3, 573.24, '2019-01-11 23:38:17'),
( 23,  5,  3,  23,  5,  5, 'Nome do Preço de Produto  23',   3, 206.93, '2019-01-11 23:38:17'),
( 24,  5,  4,  24,  6,  6, 'Nome do Preço de Produto  24',   3, 589.27, '2019-01-11 23:38:17'),
( 25,  5,  5,  25,  7,  7, 'Nome do Preço de Produto  25',  12,  96.25, '2019-01-11 23:38:17'),
( 26,  6,  1,  26,  8,  8, 'Nome do Preço de Produto  26',  13, 588.86, '2019-01-11 23:38:17'),
( 27,  6,  2,  27,  9,  9, 'Nome do Preço de Produto  27',  13,  16.19, '2019-01-11 23:38:17'),
( 28,  6,  3,  28,  1,  1, 'Nome do Preço de Produto  28',  12,  58.06, '2019-01-11 23:38:17'),
( 29,  6,  4,  29,  2,  2, 'Nome do Preço de Produto  29',  12, 390.80, '2019-01-11 23:38:17'),
( 30,  6,  5,  30,  3,  3, 'Nome do Preço de Produto  30',  12,  76.14, '2019-01-11 23:38:17'),
( 31,  7,  1,  31,  4,  4, 'Nome do Preço de Produto  31',  12, 866.61, '2019-01-11 23:38:17'),
( 32,  7,  2,  32,  5,  5, 'Nome do Preço de Produto  32',  12, 169.89, '2019-01-11 23:38:17'),
( 33,  7,  3,  33,  6,  6, 'Nome do Preço de Produto  33',  12, 474.67, '2019-01-11 23:38:17'),
( 34,  7,  4,  34,  7,  7, 'Nome do Preço de Produto  34',  13, 740.40, '2019-01-11 23:38:17'),
( 35,  7,  5,  35,  8,  8, 'Nome do Preço de Produto  35',  13, 471.95, '2019-01-11 23:38:17'),
( 36,  8,  1,  36,  9,  9, 'Nome do Preço de Produto  36',  12, 157.59, '2019-01-11 23:38:17'),
( 37,  8,  2,  37,  1,  1, 'Nome do Preço de Produto  37',  11, 663.87, '2019-01-11 23:38:17'),
( 38,  8,  3,  38,  2,  2, 'Nome do Preço de Produto  38',  11, 992.38, '2019-01-11 23:38:17'),
( 39,  8,  4,  39,  3,  3, 'Nome do Preço de Produto  39',  11, 657.20, '2019-01-11 23:38:17'),
( 40,  8,  5,  40,  4,  4, 'Nome do Preço de Produto  40',  10, 442.12, '2019-01-11 23:38:17'),
( 41,  9,  1,  41,  5,  5, 'Nome do Preço de Produto  41',   5, 611.95, '2019-01-11 23:38:17'),
( 42,  9,  2,  42,  6,  6, 'Nome do Preço de Produto  42',   5, 203.33, '2019-01-11 23:38:17'),
( 43,  9,  3,  43,  7,  7, 'Nome do Preço de Produto  43',   6, 771.98, '2019-01-11 23:38:17'),
( 44,  9,  4,  44,  8,  8, 'Nome do Preço de Produto  44',   6, 681.05, '2019-01-11 23:38:17'),
( 45,  9,  5,  45,  9,  9, 'Nome do Preço de Produto  45',   6, 405.09, '2019-01-11 23:38:17'),
( 46, 10,  1,  46,  1,  1, 'Nome do Preço de Produto  46',   6, 923.42, '2019-01-11 23:38:17'),
( 47, 10,  2,  47,  2,  2, 'Nome do Preço de Produto  47',   6, 827.31, '2019-01-11 23:38:17'),
( 48, 10,  3,  48,  3,  3, 'Nome do Preço de Produto  48',   6, 340.86, '2019-01-11 23:38:17'),
( 49, 10,  4,  49,  4,  4, 'Nome do Preço de Produto  49',  25, 430.42, '2019-01-11 23:38:17'),
( 50, 10,  5,  50,  5,  5, 'Nome do Preço de Produto  50',  25, 196.19, '2019-01-11 23:38:17'),
( 51, 11,  1,  51,  6,  6, 'Nome do Preço de Produto  51',  26, 452.63, '2019-01-11 23:38:17'),
( 52, 11,  2,  52,  7,  7, 'Nome do Preço de Produto  52',  27, 904.86, '2019-01-11 23:38:17'),
( 53, 11,  3,  53,  8,  8, 'Nome do Preço de Produto  53',  27, 636.00, '2019-01-11 23:38:17'),
( 54, 11,  4,  54,  9,  9, 'Nome do Preço de Produto  54',  27, 542.57, '2019-01-11 23:38:17'),
( 55, 11,  5,  55,  1,  1, 'Nome do Preço de Produto  55',  24, 536.50, '2019-01-11 23:38:17'),
( 56, 12,  1,  56,  2,  2, 'Nome do Preço de Produto  56',  25, 644.94, '2019-01-11 23:38:17'),
( 57, 12,  2,  57,  3,  3, 'Nome do Preço de Produto  57',  25, 144.97, '2019-01-11 23:38:17'),
( 58, 12,  3,  58,  4,  4, 'Nome do Preço de Produto  58',  24, 330.10, '2019-01-11 23:38:17'),
( 59, 12,  4,  59,  5,  5, 'Nome do Preço de Produto  59',  24, 411.23, '2019-01-11 23:38:17'),
( 60, 12,  5,  60,  6,  6, 'Nome do Preço de Produto  60',  24,  35.45, '2019-01-11 23:38:17'),
( 61, 13,  1,  61,  7,  7, 'Nome do Preço de Produto  61',  24, 820.92, '2019-01-11 23:38:17'),
( 62, 13,  2,  62,  8,  8, 'Nome do Preço de Produto  62',  24, 406.22, '2019-01-11 23:38:17'),
( 63, 13,  3,  63,  9,  9, 'Nome do Preço de Produto  63',  24, 956.95, '2019-01-11 23:38:17'),
( 64, 13,  4,  64,  1,  1, 'Nome do Preço de Produto  64',  25, 123.02, '2019-01-11 23:38:17'),
( 65, 13,  5,  65,  2,  2, 'Nome do Preço de Produto  65',  25, 550.43, '2019-01-11 23:38:17'),
( 66, 14,  1,  66,  3,  3, 'Nome do Preço de Produto  66',  24, 424.61, '2019-01-11 23:38:17'),
( 67, 14,  2,  67,  4,  4, 'Nome do Preço de Produto  67',  27,   7.84, '2019-01-11 23:38:17'),
( 68, 14,  3,  68,  5,  5, 'Nome do Preço de Produto  68',  27, 191.92, '2019-01-11 23:38:17'),
( 69, 14,  4,  69,  6,  6, 'Nome do Preço de Produto  69',  27,  35.18, '2019-01-11 23:38:17'),
( 70, 14,  5,  70,  7,  7, 'Nome do Preço de Produto  70',  26, 640.47, '2019-01-11 23:38:17'),
( 71, 15,  1,  71,  8,  8, 'Nome do Preço de Produto  71',  25, 807.91, '2019-01-11 23:38:17'),
( 72, 15,  2,  72,  9,  9, 'Nome do Preço de Produto  72',  25, 185.89, '2019-01-11 23:38:17'),
( 73, 15,  3,  73,  1,  1, 'Nome do Preço de Produto  73',  22, 794.89, '2019-01-11 23:38:17'),
( 74, 15,  4,  74,  2,  2, 'Nome do Preço de Produto  74',  22, 341.58, '2019-01-11 23:38:17'),
( 75, 15,  5,  75,  3,  3, 'Nome do Preço de Produto  75',  22, 828.26, '2019-01-11 23:38:17'),
( 76, 16,  1,  76,  4,  4, 'Nome do Preço de Produto  76',  22, 772.34, '2019-01-11 23:38:17'),
( 77, 16,  2,  77,  5,  5, 'Nome do Preço de Produto  77',  22, 737.66, '2019-01-11 23:38:17'),
( 78, 16,  3,  78,  6,  6, 'Nome do Preço de Produto  78',  22, 992.46, '2019-01-11 23:38:17'),
( 79, 16,  4,  79,  7,  7, 'Nome do Preço de Produto  79',  21, 725.50, '2019-01-11 23:38:17'),
( 80, 16,  5,  80,  8,  8, 'Nome do Preço de Produto  80',  21, 417.15, '2019-01-11 23:38:17'),
( 81, 17,  1,  81,  9,  9, 'Nome do Preço de Produto  81',  10,  96.57, '2019-01-11 23:38:17'),
( 82, 17,  2,  82,  1,  1, 'Nome do Preço de Produto  82',  11, 233.66, '2019-01-11 23:38:17'),
( 83, 17,  3,  83,  2,  2, 'Nome do Preço de Produto  83',  11, 178.28, '2019-01-11 23:38:17'),
( 84, 17,  4,  84,  3,  3, 'Nome do Preço de Produto  84',  11, 376.31, '2019-01-11 23:38:17'),
( 85, 17,  5,  85,  4,  4, 'Nome do Preço de Produto  85',  12, 570.61, '2019-01-11 23:38:17'),
( 86, 18,  1,  86,  5,  5, 'Nome do Preço de Produto  86',  13, 362.62, '2019-01-11 23:38:17'),
( 87, 18,  2,  87,  6,  6, 'Nome do Preço de Produto  87',  13, 996.21, '2019-01-11 23:38:17'),
( 88, 18,  3,  88,  7,  7, 'Nome do Preço de Produto  88',  12, 241.04, '2019-01-11 23:38:17'),
( 89, 18,  4,  89,  8,  8, 'Nome do Preço de Produto  89',  12, 900.79, '2019-01-11 23:38:17'),
( 90, 18,  5,  90,  9,  9, 'Nome do Preço de Produto  90',  12, 395.00, '2019-01-11 23:38:17'),
( 91, 19,  1,  91,  1,  1, 'Nome do Preço de Produto  91',  12,  99.50, '2019-01-11 23:38:17'),
( 92, 19,  2,  92,  2,  2, 'Nome do Preço de Produto  92',  12,  64.81, '2019-01-11 23:38:17'),
( 93, 19,  3,  93,  3,  3, 'Nome do Preço de Produto  93',  12, 876.15, '2019-01-11 23:38:17'),
( 94, 19,  4,  94,  4,  4, 'Nome do Preço de Produto  94',  13, 194.37, '2019-01-11 23:38:17'),
( 95, 19,  5,  95,  5,  5, 'Nome do Preço de Produto  95',  13, 462.70, '2019-01-11 23:38:17'),
( 96, 20,  1,  96,  6,  6, 'Nome do Preço de Produto  96',  12, 572.77, '2019-01-11 23:38:17'),
( 97, 20,  2,  97,  7,  7, 'Nome do Preço de Produto  97',  51, 301.40, '2019-01-11 23:38:17'),
( 98, 20,  3,  98,  8,  8, 'Nome do Preço de Produto  98',  51, 367.02, '2019-01-11 23:38:17'),
( 99, 20,  4,  99,  9,  9, 'Nome do Preço de Produto  99',  51, 425.02, '2019-01-11 23:38:17'),
(100, 20,  5, 100,  1,  1, 'Nome do Preço de Produto 100',  50, 965.90, '2019-01-11 23:38:17'),
(101, 21,  1, 101,  2,  2, 'Nome do Preço de Produto 101',  53, 360.52, '2019-01-11 23:38:17'),
(102, 21,  2, 102,  3,  3, 'Nome do Preço de Produto 102',  53, 842.60, '2019-01-11 23:38:17'),
(103, 21,  3, 103,  4,  4, 'Nome do Preço de Produto 103',  54, 830.12, '2019-01-11 23:38:17'),
(104, 21,  4, 104,  5,  5, 'Nome do Preço de Produto 104',  54,  27.04, '2019-01-11 23:38:17'),
(105, 21,  5, 105,  6,  6, 'Nome do Preço de Produto 105',  54, 216.55, '2019-01-11 23:38:17'),
(106, 22,  1, 106,  7,  7, 'Nome do Preço de Produto 106',  54, 643.37, '2019-01-11 23:38:17'),
(107, 22,  2, 107,  8,  8, 'Nome do Preço de Produto 107',  54, 959.55, '2019-01-11 23:38:17'),
(108, 22,  3, 108,  9,  9, 'Nome do Preço de Produto 108',  54, 629.81, '2019-01-11 23:38:17'),
(109, 22,  4, 109,  1,  1, 'Nome do Preço de Produto 109',  49, 662.93, '2019-01-11 23:38:17'),
(110, 22,  5, 110,  2,  2, 'Nome do Preço de Produto 110',  49, 478.10, '2019-01-11 23:38:17'),
(111, 23,  1, 111,  3,  3, 'Nome do Preço de Produto 111',  50,  52.23, '2019-01-11 23:38:17'),
(112, 23,  2, 112,  4,  4, 'Nome do Preço de Produto 112',  51, 531.67, '2019-01-11 23:38:17'),
(113, 23,  3, 113,  5,  5, 'Nome do Preço de Produto 113',  51, 431.82, '2019-01-11 23:38:17'),
(114, 23,  4, 114,  6,  6, 'Nome do Preço de Produto 114',  51, 670.28, '2019-01-11 23:38:17'),
(115, 23,  5, 115,  7,  7, 'Nome do Preço de Produto 115',  48, 270.98, '2019-01-11 23:38:17'),
(116, 24,  1, 116,  8,  8, 'Nome do Preço de Produto 116',  49, 522.41, '2019-01-11 23:38:17'),
(117, 24,  2, 117,  9,  9, 'Nome do Preço de Produto 117',  49, 400.37, '2019-01-11 23:38:17'),
(118, 24,  3, 118,  1,  1, 'Nome do Preço de Produto 118',  48, 384.61, '2019-01-11 23:38:17'),
(119, 24,  4, 119,  2,  2, 'Nome do Preço de Produto 119',  48, 349.29, '2019-01-11 23:38:17'),
(120, 24,  5, 120,  3,  3, 'Nome do Preço de Produto 120',  48, 891.79, '2019-01-11 23:38:17'),
(121, 25,  1, 121,  4,  4, 'Nome do Preço de Produto 121',  48, 938.96, '2019-01-11 23:38:17'),
(122, 25,  2, 122,  5,  5, 'Nome do Preço de Produto 122',  48, 277.28, '2019-01-11 23:38:17'),
(123, 25,  3, 123,  6,  6, 'Nome do Preço de Produto 123',  48,  17.80, '2019-01-11 23:38:17'),
(124, 25,  4, 124,  7,  7, 'Nome do Preço de Produto 124',  49, 765.90, '2019-01-11 23:38:17'),
(125, 25,  5, 125,  8,  8, 'Nome do Preço de Produto 125',  49, 871.76, '2019-01-11 23:38:17'),
(126, 26,  1, 126,  9,  9, 'Nome do Preço de Produto 126',  48, 761.42, '2019-01-11 23:38:17'),
(127, 26,  2, 127,  1,  1, 'Nome do Preço de Produto 127',  51, 130.39, '2019-01-11 23:38:17'),
(128, 26,  3, 128,  2,  2, 'Nome do Preço de Produto 128',  51,  54.82, '2019-01-11 23:38:17'),
(129, 26,  4, 129,  3,  3, 'Nome do Preço de Produto 129',  51, 362.00, '2019-01-11 23:38:17'),
(130, 26,  5, 130,  4,  4, 'Nome do Preço de Produto 130',  50, 715.39, '2019-01-11 23:38:17'),
(131, 27,  1, 131,  5,  5, 'Nome do Preço de Produto 131',  49, 127.05, '2019-01-11 23:38:17'),
(132, 27,  2, 132,  6,  6, 'Nome do Preço de Produto 132',  49, 317.95, '2019-01-11 23:38:17'),
(133, 27,  3, 133,  7,  7, 'Nome do Preço de Produto 133',  54, 938.77, '2019-01-11 23:38:17'),
(134, 27,  4, 134,  8,  8, 'Nome do Preço de Produto 134',  54, 856.37, '2019-01-11 23:38:17'),
(135, 27,  5, 135,  9,  9, 'Nome do Preço de Produto 135',  54, 274.33, '2019-01-11 23:38:17'),
(136, 28,  1, 136,  1,  1, 'Nome do Preço de Produto 136',  54, 614.02, '2019-01-11 23:38:17'),
(137, 28,  2, 137,  2,  2, 'Nome do Preço de Produto 137',  54, 415.92, '2019-01-11 23:38:17'),
(138, 28,  3, 138,  3,  3, 'Nome do Preço de Produto 138',  54, 825.08, '2019-01-11 23:38:17'),
(139, 28,  4, 139,  4,  4, 'Nome do Preço de Produto 139',  53, 708.66, '2019-01-11 23:38:17'),
(140, 28,  5, 140,  5,  5, 'Nome do Preço de Produto 140',  53, 990.50, '2019-01-11 23:38:17'),
(141, 29,  1, 141,  6,  6, 'Nome do Preço de Produto 141',  50, 387.27, '2019-01-11 23:38:17'),
(142, 29,  2, 142,  7,  7, 'Nome do Preço de Produto 142',  51, 489.08, '2019-01-11 23:38:17'),
(143, 29,  3, 143,  8,  8, 'Nome do Preço de Produto 143',  51, 275.86, '2019-01-11 23:38:17'),
(144, 29,  4, 144,  9,  9, 'Nome do Preço de Produto 144',  51, 518.83, '2019-01-11 23:38:17'),
(145, 29,  5, 145,  1,  1, 'Nome do Preço de Produto 145',  44, 405.96, '2019-01-11 23:38:17'),
(146, 30,  1, 146,  2,  2, 'Nome do Preço de Produto 146',  45, 982.81, '2019-01-11 23:38:17'),
(147, 30,  2, 147,  3,  3, 'Nome do Preço de Produto 147',  45,  70.61, '2019-01-11 23:38:17'),
(148, 30,  3, 148,  4,  4, 'Nome do Preço de Produto 148',  44, 933.73, '2019-01-11 23:38:17'),
(149, 30,  4, 149,  5,  5, 'Nome do Preço de Produto 149',  44,   1.22, '2019-01-11 23:38:17'),
(150, 30,  5, 150,  6,  6, 'Nome do Preço de Produto 150',  44, 538.24, '2019-01-11 23:38:17'),
(151, 31,  1, 151,  7,  7, 'Nome do Preço de Produto 151',  44, 851.28, '2019-01-11 23:38:17'),
(152, 31,  2, 152,  8,  8, 'Nome do Preço de Produto 152',  44, 643.71, '2019-01-11 23:38:17'),
(153, 31,  3, 153,  9,  9, 'Nome do Preço de Produto 153',  44, 387.50, '2019-01-11 23:38:17'),
(154, 31,  4, 154,  1,  1, 'Nome do Preço de Produto 154',  45,  13.55, '2019-01-11 23:38:17'),
(155, 31,  5, 155,  2,  2, 'Nome do Preço de Produto 155',  45, 693.55, '2019-01-11 23:38:17'),
(156, 32,  1, 156,  3,  3, 'Nome do Preço de Produto 156',  44, 559.94, '2019-01-11 23:38:17'),
(157, 32,  2, 157,  4,  4, 'Nome do Preço de Produto 157',  43, 600.78, '2019-01-11 23:38:17'),
(158, 32,  3, 158,  5,  5, 'Nome do Preço de Produto 158',  43, 821.93, '2019-01-11 23:38:17'),
(159, 32,  4, 159,  6,  6, 'Nome do Preço de Produto 159',  43, 579.24, '2019-01-11 23:38:17'),
(160, 32,  5, 160,  7,  7, 'Nome do Preço de Produto 160',  42,  74.71, '2019-01-11 23:38:17'),
(161, 33,  1, 161,  8,  8, 'Nome do Preço de Produto 161',  21, 331.57, '2019-01-11 23:38:17'),
(162, 33,  2, 162,  9,  9, 'Nome do Preço de Produto 162',  21, 506.06, '2019-01-11 23:38:17'),
(163, 33,  3, 163,  1,  1, 'Nome do Preço de Produto 163',  22, 507.00, '2019-01-11 23:38:17'),
(164, 33,  4, 164,  2,  2, 'Nome do Preço de Produto 164',  22, 772.15, '2019-01-11 23:38:17'),
(165, 33,  5, 165,  3,  3, 'Nome do Preço de Produto 165',  22, 688.41, '2019-01-11 23:38:17'),
(166, 34,  1, 166,  4,  4, 'Nome do Preço de Produto 166',  22, 728.63, '2019-01-11 23:38:17'),
(167, 34,  2, 167,  5,  5, 'Nome do Preço de Produto 167',  22, 359.54, '2019-01-11 23:38:17'),
(168, 34,  3, 168,  6,  6, 'Nome do Preço de Produto 168',  22, 430.48, '2019-01-11 23:38:17'),
(169, 34,  4, 169,  7,  7, 'Nome do Preço de Produto 169',  25, 227.11, '2019-01-11 23:38:17'),
(170, 34,  5, 170,  8,  8, 'Nome do Preço de Produto 170',  25, 466.31, '2019-01-11 23:38:17'),
(171, 35,  1, 171,  9,  9, 'Nome do Preço de Produto 171',  26, 853.13, '2019-01-11 23:38:17'),
(172, 35,  2, 172,  1,  1, 'Nome do Preço de Produto 172',  27, 878.98, '2019-01-11 23:38:17'),
(173, 35,  3, 173,  2,  2, 'Nome do Preço de Produto 173',  27, 977.62, '2019-01-11 23:38:17'),
(174, 35,  4, 174,  3,  3, 'Nome do Preço de Produto 174',  27, 131.92, '2019-01-11 23:38:17'),
(175, 35,  5, 175,  4,  4, 'Nome do Preço de Produto 175',  24, 922.34, '2019-01-11 23:38:17'),
(176, 36,  1, 176,  5,  5, 'Nome do Preço de Produto 176',  25, 970.06, '2019-01-11 23:38:17'),
(177, 36,  2, 177,  6,  6, 'Nome do Preço de Produto 177',  25,  56.96, '2019-01-11 23:38:17'),
(178, 36,  3, 178,  7,  7, 'Nome do Preço de Produto 178',  24, 413.05, '2019-01-11 23:38:17'),
(179, 36,  4, 179,  8,  8, 'Nome do Preço de Produto 179',  24, 614.25, '2019-01-11 23:38:17'),
(180, 36,  5, 180,  9,  9, 'Nome do Preço de Produto 180',  24, 899.74, '2019-01-11 23:38:17'),
(181, 37,  1, 181,  1,  1, 'Nome do Preço de Produto 181',  24, 194.42, '2019-01-11 23:38:17'),
(182, 37,  2, 182,  2,  2, 'Nome do Preço de Produto 182',  24, 674.72, '2019-01-11 23:38:17'),
(183, 37,  3, 183,  3,  3, 'Nome do Preço de Produto 183',  24, 384.87, '2019-01-11 23:38:17'),
(184, 37,  4, 184,  4,  4, 'Nome do Preço de Produto 184',  25,  84.91, '2019-01-11 23:38:17'),
(185, 37,  5, 185,  5,  5, 'Nome do Preço de Produto 185',  25, 708.54, '2019-01-11 23:38:17'),
(186, 38,  1, 186,  6,  6, 'Nome do Preço de Produto 186',  24, 819.52, '2019-01-11 23:38:17'),
(187, 38,  2, 187,  7,  7, 'Nome do Preço de Produto 187',  27,  16.54, '2019-01-11 23:38:17'),
(188, 38,  3, 188,  8,  8, 'Nome do Preço de Produto 188',  27, 632.31, '2019-01-11 23:38:17'),
(189, 38,  4, 189,  9,  9, 'Nome do Preço de Produto 189',  27, 160.22, '2019-01-11 23:38:17'),
(190, 38,  5, 190,  1,  1, 'Nome do Preço de Produto 190',  26,  66.80, '2019-01-11 23:38:17'),
(191, 39,  1, 191,  2,  2, 'Nome do Preço de Produto 191',  25, 313.53, '2019-01-11 23:38:17'),
(192, 39,  2, 192,  3,  3, 'Nome do Preço de Produto 192',  25, 641.77, '2019-01-11 23:38:17'),
(193, 39,  3, 193,  4,  4, 'Nome do Preço de Produto 193', 102, 582.50, '2019-01-11 23:38:17'),
(194, 39,  4, 194,  5,  5, 'Nome do Preço de Produto 194', 102, 582.62, '2019-01-11 23:38:17'),
(195, 39,  5, 195,  6,  6, 'Nome do Preço de Produto 195', 102, 816.67, '2019-01-11 23:38:17'),
(196, 40,  1, 196,  7,  7, 'Nome do Preço de Produto 196', 102, 962.58, '2019-01-11 23:38:17'),
(197, 40,  2, 197,  8,  8, 'Nome do Preço de Produto 197', 102, 718.51, '2019-01-11 23:38:17'),
(198, 40,  3, 198,  9,  9, 'Nome do Preço de Produto 198', 102, 396.52, '2019-01-11 23:38:17'),
(199, 40,  4, 199,  1,  1, 'Nome do Preço de Produto 199', 101, 905.19, '2019-01-11 23:38:17'),
(200, 40,  5, 200,  2,  2, 'Nome do Preço de Produto 200', 101, 596.74, '2019-01-11 23:38:17');
