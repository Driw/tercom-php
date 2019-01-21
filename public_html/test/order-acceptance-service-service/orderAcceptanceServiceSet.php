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
	<p>Preço de Serviço Aceito ID: <input type='text' name='idOrderAcceptanceService' required></p>
	<p>* Quantidade Solicitada: <input type='text' name='amountRequest'></p>
	<p>* Subpreço: <input type='text' name='subprice'></p>
	<p>* Observações: <input type='text' name='observations'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderAcceptance = $_GET['idOrderAcceptance'];
		$idOrderAcceptanceService = $_GET['idOrderAcceptanceService'];
		return GeradorDeDados::callWebService("orderAcceptanceService/set/$idOrderAcceptance/$idOrderAcceptanceService", $_GET, true);
	}
}
include '../execute.php';

