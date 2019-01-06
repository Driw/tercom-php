<?php

$qtd = isset($_GET['qtd']) ? intval($_GET['qtd']) : 1;
$resultados = [];

for ($i = 0; $i < $qtd; $i++)
	array_push($resultados, testExecute());

header('Content-type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
error_reporting(E_ALL);

echo json_encode($resultados);

