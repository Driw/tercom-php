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
	<p>Preço de Produto Aceito ID: <input type='text' name='idOrderAcceptanceProduct' required></p>
	<p>* Quantidade Solicitada: <input type='text' name='amountRequest'></p>
	<p>* Subpreço: <input type='text' name='subprice'></p>
	<p>* Observações: <input type='text' name='observations'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderAcceptance = $_GET['idOrderAcceptance'];
		$idOrderAcceptanceProduct = $_GET['idOrderAcceptanceProduct'];
		return GeradorDeDados::callWebService("orderAcceptanceProduct/set/$idOrderAcceptance/$idOrderAcceptanceProduct", $_GET, true);
	}
}
include '../execute.php';

