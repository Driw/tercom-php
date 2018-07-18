<?php

$qtd = isset($_GET['qtd']) ? intval($_GET['qtd']) : 1;
$resultados = [];

for ($i = 0; $i < $qtd; $i++)
	array_push($resultados, testExecute());

echo json_encode($resultados);

?>