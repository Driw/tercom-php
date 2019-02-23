<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idService']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Serviço ID: <input type='text' name='idService' required></p>
	<p>Cliente Serviço ID: <input type='text' name='idServiceCustomer' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idService = intval($_GET['idService']);
		$idServiceCustomer = $_GET['idServiceCustomer'];

		return GeradorDeDados::callWebService("service/setCustomerId/$idService/$idServiceCustomer", []);
	}
}
include_once '../execute.php';

