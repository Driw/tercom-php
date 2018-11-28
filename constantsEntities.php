<?php

define('MIN_NAME_LEN',				3);
define('MAX_NAME_LEN',				48);
define('MAX_EMAIL_LEN',				48);
define('MIN_PASSWORD_LEN',			6);
define('MAX_PASSWORD_LEN',			24);

define('MIN_PRODUCT_PACKAGE_LEN',	MIN_NAME_LEN);
define('MAX_PRODUCT_PACKAGE_LEN',	32);
define('MIN_PRODUCT_TYPE_LEN',		MIN_NAME_LEN);
define('MAX_PRODUCT_TYPE_LEN',		32);
define('MIN_PRODUCT_UNIT_LEN',		MIN_NAME_LEN);
define('MAX_PRODUCT_UNIT_LEN',		32);
define('MIN_PRODUCT_INITIALS_LEN',	1);
define('MAX_PRODUCT_INITIALS_LEN',	6);

define('PATTERN_PASSWORD',			'/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/');

?>