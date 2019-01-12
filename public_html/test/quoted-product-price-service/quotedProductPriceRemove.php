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
	<p>Preço de Produto Cotado ID: <input type='text' name='idQuotedOrderProduct' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$idQuotedOrderProduct = $_GET['idQuotedOrderProduct'];

		return GeradorDeDados::callWebService("quotedProductPrice/remove/$idOrderRequest/$idQuotedOrderProduct", []);
	}
}
include '../execute.php';

