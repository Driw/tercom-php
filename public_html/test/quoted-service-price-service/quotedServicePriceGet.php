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
	<p>Preço de Serviço Cotado ID: <input type='text' name='idQuotedOrderService' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$idQuotedOrderService = $_GET['idQuotedOrderService'];

		return GeradorDeDados::callWebService("quotedServicePrice/get/$idOrderRequest/$idQuotedOrderService", []);
	}
}
include '../execute.php';

