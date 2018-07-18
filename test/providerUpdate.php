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
	<input type='text' name='idProvider'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}

	$idProvider = intval($_GET['idProvider']);
	$empresa = GeradorDeDados::genEmpresa();
	$pessoa = GeradorDeDados::genPessoa();
	$parameters = [
		'cnpj' => $empresa['cnpj'],
		'companyName' => $empresa['nome']. '(CN)',
		'fantasyName' => $empresa['nome'],
		'spokesman' => $pessoa['nome'],
		'site' => $empresa['site'],
	];
	return GeradorDeDados::callWebService("provider/set/$idProvider", $parameters);
}
include_once 'execute.php';

?>