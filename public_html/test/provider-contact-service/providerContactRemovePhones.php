<?php

use tercom\GeradorDeDados;
use tercom\entities\Phone;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProvider']) || !isset($_GET['idProviderContact']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>Apenas do Contato ID: <input type='text' name='idProviderContact' required></p>
	<p>até Contato ID (optional): <input type='text' name='idProviderContactLast'></p>
	<p><select name='phoneType' required>
		<option value='0'>Ambos</option>
		<option value='1'>Comercial</option>
		<option value='2'>Secundário</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
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
}
include_once '../execute.php';

?>