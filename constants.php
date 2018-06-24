<?php

/* CONFIGURAÇÕES GERAIS */

define('PHP_VERSION_MIN',	'7.0');
define('DOMAIN',			sprintf('https://%s/', $_SERVER['SERVER_NAME']));
define('WWW_DOMAIN',		sprintf('https://www.%s/', $_SERVER['SERVER_NAME']));
define('SYS_DEVELOP',		!strcmp($_SERVER['REMOTE_ADDR'], '127.0.0.1') && !isset($_REQUEST['nodev']));
define('PHP_ENCRYPT_KEY',	'ZlnBMzWyoG7OHAyhP7ooKmgM');

/* DIRETÓRIOS */

define('DIR_DATA',		$_SERVER['DOCUMENT_ROOT']. '/data/');
define('DIR_SYSTEM',	$_SERVER['DOCUMENT_ROOT']. '/system/');

define('DIR_CONFIGS',	DIR_DATA. 'conf/');
define('DIR_STYLES',	DIR_DATA. 'css/');
define('DIR_JSCRIPS',	DIR_DATA. 'js/');
define('DIR_MAILS',		DIR_DATA. 'mails/');

/* CARACTERES */

define('BREAK_LINE',	PHP_EOL);

define('DATE_TIME_SQL_FORMAT',	'Y-m-d H:i:s');
define('DATE_SQL_FORMAT',		'Y-m-d');
define('TIME_SQL_FORMAT',		'H:i:s');
define('DATE_TIME_NS_FORMAT',	'YmdHis');
define('DATE_NS_FORMAT',		'Ymd');
define('TIME_NS_FORMAT',		'His');
define('DATE_TIME_BR_FORMAT',	'H:i:s d/m/Y');
define('DATE_BR_FORMAT',		'd/m/Y');
define('TIME_BR_FORMAT',		'H:i:s');

/* E-MAILS */

define('MAIL_ADDRESS_NO_REPLY',	'noreply@diverproject.org');
define('MAIL_ADDRESS_LOG',		'noreply-log@diverproject.org');

/* BANCO DE DADOS */

/* OUTROS */

require_once 'constantsEntities.php';

?>