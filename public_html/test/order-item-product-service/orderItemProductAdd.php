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
	<p>Solicitação de Pedido ID: <input type='text' name='idOrderRequest' required></p>
	<p>Produto ID: <input type='text' name='idProduct' required></p>
	<p>*Fornecedor ID: <input type='text' name='idProvider'></p>
	<p>*Fabricante ID: <input type='text' name='idManufacturer'></p>
	<p>Melhor Preço: <input type='checkbox' name='betterPrice' value='1'></p>
	<p>*Observação: <input type='text' name='observation'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];
		$_GET['betterPrice'] = isset($_GET['betterPrice']);

		return GeradorDeDados::callWebService("orderItemProduct/add/$idOrderRequest", $_GET, true);
	}
}
include_once '../execute.php';

