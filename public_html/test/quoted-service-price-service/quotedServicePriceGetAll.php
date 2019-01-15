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
	<p>Solicitação de Pedido de Cotação ID: <input type='text' name='idOrderRequest' required></p>
	<p>Item de Serviço de Pedido ID: <input type='text' name='idOrderItemService' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$idOrderItemService = $_GET['idOrderItemService'];

		return GeradorDeDados::callWebService("quotedServicePrice/getAll/$idOrderRequest/$idOrderItemService", []);
	}
}
include '../execute.php';

