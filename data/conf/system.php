<?php

return [
	'HeadTitle'			=> 'TERCOM',
	'HeadCharset'		=> 'UTF-8',
	'JQueryVersion'		=> '3.3.1',
	'TimeZone'			=> 'America/Sao_Paulo',
	'Locale'			=> 'pt_BR',

	'StyleSheets'			=> [
		'bootstrap.min',
		'bootstrap-grid',
		'bootstrap-dialog.min',
		'bootstrap-datetimepicker.min',
		'open-iconic-bootstrap.min',
		'fontawesome-all.min',
		'fonts',
		'style',
	],

	'JavaScripts'			=> [
		'bootstrap.min',
		'bootstrap-dialog.min',
		'jquery.validate.min',
		'jquery.validate-additional-methods.min',
		'jquery.validate-messages_pt_BR.min',
		'bootstrap-datetimepicker.min',
		'bootstrap-datetimepicker.pt-BR',
		'jquery.mask.min',
		'system.min'
	],

	'JavaScriptsOnEnd'		=> [
	],

	'MySQL'				=> [
		'System'			=> [
			'Host'				=> 'localhost',
			'Username'			=> 'tercom',
			'Password'			=> 'HwBPbbBv3oFCTIon',
			'Database'			=> 'tercom',
			'Charset'			=> 'utf8',
		]
	],

	'Mail'				=> [
		'Host'					=> 'mail.diverproject.org',
		'SMTPAuth'				=> true,
		'Username'				=> MAIL_ADDRESS_NO_REPLY,
		'Password'				=> '$mWZ4(bIp6Yq',
		'SMTPSecure'			=> 'tls',
		'Port'					=> '587',
	],
];

?>
