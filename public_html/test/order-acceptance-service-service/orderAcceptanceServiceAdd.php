<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderAcceptance']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Aceitação de Pedido ID: <input type='text' name='idOrderAcceptance' required></p>
	<p>Cotação de Preço de Serviço ID: <input type='text' name='idQuotedServicePrice' required></p>
	<p>Quantidade Solicitada: <input type='text' name='amountRequest' required></p>
	<p>* Subpreço: <input type='text' name='subprice'></p>
	<p>* Observações: <input type='text' name='observations'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderAcceptance = $_GET['idOrderAcceptance'];
		$idQuotedServicePrice = $_GET['idQuotedServicePrice'];
		return GeradorDeDados::callWebService("orderAcceptanceService/add/$idOrderAcceptance/$idQuotedServicePrice", $_GET, true);
	}
}
include '../execute.php';

