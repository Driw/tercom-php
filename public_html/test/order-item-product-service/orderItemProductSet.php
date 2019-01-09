<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderItemProduct']))
		{
			header('Content-type: text/html');

?>
<form method='get'>
	<p>Solicitação Pedido ID: <input type='text' name=idOrderRequest required></p>
	<p>Pedido Item Produto ID: <input type='text' name='idOrderItemProduct' required></p>
	<p>*Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>*Fabricante ID: <input type='text' name='idManufacturer' required></p>
	<p>Melhor Preço: <input type='checkbox' name='betterPrice' value='1'></p>
	<p>*Observação: <input type='text' name='observation'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$idOrderItemProduct = $_GET['idOrderItemProduct'];
		$_GET['betterPrice'] = isset($_GET['betterPrice']);

		return GeradorDeDados::callWebService("orderItemProduct/set/$idOrderRequest/$idOrderItemProduct", $_GET, true);
	}
}
include_once '../execute.php';

