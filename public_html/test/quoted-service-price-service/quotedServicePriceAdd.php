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
	<p>Item de Serviço de Cotação ID: <input type='text' name='idOrderItemService' required></p>
	<p>Preço de Serviço ID: <input type='text' name='idServicePrice' required></p>
	<p>*Observação: <input type='text' name='observation'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderQuote = $_GET['idOrderQuote'];
		$idOrderItemService = $_GET['idOrderItemService'];
		$idServicePrice = $_GET['idServicePrice'];

		return GeradorDeDados::callWebService("quotedServicePrice/add/$idOrderQuote/$idOrderItemService/$idServicePrice", $_GET, true);
	}
}
include '../execute.php';

