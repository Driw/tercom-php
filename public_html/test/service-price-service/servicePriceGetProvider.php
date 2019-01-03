<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idService']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Serviço ID: <input type='text' name='idService' required></p>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idService = intval($_GET['idService']);
		$idProvider = intval($_GET['idProvider']);
		return GeradorDeDados::callWebService("servicePrice/getProvider/$idService/$idProvider", []);
	}
}
require_once '../execute.php';

