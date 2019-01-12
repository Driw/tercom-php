<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderQuote']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>ID Cotação de Pedido: <input type='text' name='idOrderQuote' required></p>
	<p>Item de Produto de Cotação ID: <input type='text' name='idOrderItemProduct' required></p>
	<p>Preço de Produto ID: <input type='text' name='idProductPrice' required></p>
	<p>*Observação: <input type='text' name='observation'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderQuote = $_GET['idOrderQuote'];
		$idOrderItemProduct = $_GET['idOrderItemProduct'];
		$idProductPrice = $_GET['idProductPrice'];

		return GeradorDeDados::callWebService("quotedProductPrice/add/$idOrderQuote/$idOrderItemProduct/$idProductPrice", $_GET, true);
	}
}
include '../execute.php';

