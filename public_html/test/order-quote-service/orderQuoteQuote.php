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
	<p>Solicitação de Pedido de Cotação ID: <input type='text' name='idOrderRequest'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderRequest = $_GET['idOrderRequest'];
		return GeradorDeDados::callWebService("orderQuote/quote/$idOrderRequest", $_GET);
	}
}
include '../execute.php';

