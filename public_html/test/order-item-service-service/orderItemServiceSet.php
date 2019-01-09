<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderItemService']))
		{
			header('Content-type: text/html');

?>
<form method='get'>
	<p>Solicitação Pedido ID: <input type='text' name=idOrderRequest required></p>
	<p>Pedido Item Serviço ID: <input type='text' name='idOrderItemService' required></p>
	<p>*Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>Melhor Preço: <input type='checkbox' name='betterPrice' value='1'></p>
	<p>*Observação: <input type='text' name='observations'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$idOrderItemService = $_GET['idOrderItemService'];
		$_GET['betterPrice'] = isset($_GET['betterPrice']);

		return GeradorDeDados::callWebService("orderItemService/set/$idOrderRequest/$idOrderItemService", $_GET, true);
	}
}
include_once '../execute.php';

