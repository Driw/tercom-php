<?php

use tercom\GeradorDeDados;
use tercom\entities\Phone;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']) || !isset($_GET['idProviderContact']))
	{
		header('Content-type: text/html');
?>
<form method='get'>
	Todos do Fornecedor ID: <input type='text' name='idProvider'>
	Apenas do Contato ID: <input type='text' name='idProviderContact'>
	até Contato ID (optional): <input type='text' name='idProviderContactLast'>
	<select name='phoneType'>
		<option value='0'>Ambos</option>
		<option value='1'>Comercial</option>
		<option value='2'>Secundário</option>
	</select>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProvider = intval($_GET['idProvider']);
	$idProviderContact = intval($_GET['idProviderContact']);
	$idProviderContactLast = intval($_GET['idProviderContactLast']);
	$phoneType = intval($_GET['phoneType']);
	$resultados = [];

	if ($idProviderContactLast === 0) $idProviderContactLast = $idProviderContact;

	do {

		$parameters = [
			'id' => $idProviderContact,
		];

		if ($phoneType === 0 || $phoneType === 1)
			array_push($resultados, GeradorDeDados::callWebService("providerContact/removeCommercial/$idProvider", $parameters));

		if ($phoneType === 0 || $phoneType === 2)
			array_push($resultados, GeradorDeDados::callWebService("providerContact/removeOtherphone/$idProvider", $parameters));

	} while (++$idProviderContact < $idProviderContactLast);

	return $resultados;
}
include_once 'execute.php';

?>