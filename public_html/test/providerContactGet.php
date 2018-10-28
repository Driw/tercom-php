<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']) || !isset($_GET['idProviderContact']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Fornecedor ID: <input type='text' name='idProvider'>
	Fornecedor Contato ID: <input type='text' name='idProviderContact'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProvider = intval($_GET['idProvider']);
	$idProviderContact = intval($_GET['idProviderContact']);
	$parameters = [
		'id' => $idProviderContact,
	];
	return GeradorDeDados::callWebService("providerContact/getContact/$idProvider", $parameters);
}
include_once 'execute.php';

?>