<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderRequest']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
<?php include '../loginCustomer.php' ?>
	<p>Solicitação de Pedido ID: <input type='text' name='idOrderRequest' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];

		return GeradorDeDados::callWebService("orderRequest/get/$idOrderRequest", $_GET);
	}
}
include '../execute.php';

