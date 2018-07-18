<?php

use tercom\GeradorDeDados;

$resultados = [];

for ($i = 0; $i < intval($_GET['qtd']); $i++)
switch ($_GET['action'])
{
	default:
		$nomeGenerator = $_GET['action'];
		if (method_exists(GeradorDeDados::class, $nomeGenerator))
			array_push($resultados, GeradorDeDados::$nomeGenerator());
		break;
}

header('Content-type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

echo json_encode($resultados);

?>