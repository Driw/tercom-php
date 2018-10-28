<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']))
	{
		header('Content-type: text/html');
?>
<form method='get'>
	Fornecedor ID: <input type='text' name='idProvider'>
	Quantos? <input type='text' name='qtd'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProvider = intval($_GET['idProvider']);
	$pessoa = GeradorDeDados::genPessoa();
	$parameters = [
		'name' => $pessoa['nome'],
		'email' => $pessoa['email'],
		'position' => GeradorDeDados::genCargo(),
	];
	return GeradorDeDados::callWebService("providerContact/add/$idProvider", $parameters);
}
include_once 'execute.php';

?>