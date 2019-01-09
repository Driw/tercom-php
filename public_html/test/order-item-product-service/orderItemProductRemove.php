<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderRequest']))
		{
			header('Content-type: text/html');

?>
<form method='get'>
	<p>Pedido de Cotação ID: <input type='text' name='idOrderRequest' required></p>
	<p>Pedido Item Produto ID: <input type='text' name='idOrderItemProduct' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$idOrderItemProduct = $_GET['idOrderItemProduct'];

		return GeradorDeDados::callWebService("orderItemProduct/remove/$idOrderRequest/$idOrderItemProduct", []);
	}
}
include_once '../execute.php';

