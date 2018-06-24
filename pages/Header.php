<?php

use dProject\Primitive\UrlFriendly;

$time = time();

global $CONFIG;

?>
<!-- Inicio do Corpo -->
<head>
	<!-- Título-->
	<title><?php echo $CONFIG->getHeadTitle(); ?></title>

	<!-- Propriedades -->
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CONFIG->getHeadCharset(); ?>">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="author" content="Andrew Mello da Silva|Rafael Nociolino Brunner">
	<meta name="description" content="Intermédio entre comprador e fornecedores, realiza cotações.">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<base href="<?php echo sprintf('%s://%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['SERVER_NAME']); ?>">
	<!-- Fim das Propriedades -->

	<!-- Estilos -->
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<?php
	foreach ($CONFIG->getStyleSheets()->toArray() as $filename)
		echo "	<link rel='stylesheet' href='/css/$filename.css?$time' type='text/css' media='screen'>".PHP_EOL;
?>
	<!-- Fim dos Estilos -->

	<!-- Scripts -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-<?php echo $CONFIG->getJQueryVersion(); ?>.min.js"></script>
	<script type='text/javascript' src="https://code.jquery.com/jquery-latest.min.js"></script>
<?php
	foreach ($CONFIG->getJavaScripts()->toArray() as $filename)
		echo "	<script type='text/javascript' src='/js/$filename.js?$time'></script>".PHP_EOL;

	$path = UrlFriendly::getBaseFront(UrlFriendly::getBaseLevel());
	$filename = sprintf('%s%s.js', DIR_PAGES, substr($path, 0, -1));
	$filenameMinify = sprintf('%s%s.min.js', DIR_PAGES, substr($path, 0, -1));

	if (file_exists($filenameMinify))
		echo "	<script type='text/javascript' src='$filenameMinify?$time'></script>".PHP_EOL;
	else if (file_exists($filename))
		echo "	<script type='text/javascript' src='$filename?$time'></script>".PHP_EOL;
?>
	<!-- Fim dos Scripts -->
</head>
<!-- Fim do Cabeçalho -->
