<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProvider']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>Apenas do Contato ID: <input type='text' name='idProviderContact' required></p>
	<p>até Contato ID (optional): <input type='text' name='idProviderContactLast'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProvider = intval($_GET['idProvider']);
		$idProviderContact = intval($_GET['idProviderContact']);
		$idProviderContactLast = intval($_GET['idProviderContactLast']);
		$resultados = [];

		if ($idProviderContactLast === 0) $idProviderContactLast = $idProviderContact;

		do {

			$parameters = [
				'id' => $idProviderContact,
			];
			array_push($resultados, GeradorDeDados::callWebService("providerContact/removeContact/$idProvider", $parameters));

		} while (++$idProviderContact < $idProviderContactLast);

		return $resultados;
	}
}
include_once '../execute.php';

?>