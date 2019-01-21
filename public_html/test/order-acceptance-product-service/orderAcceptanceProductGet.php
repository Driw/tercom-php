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
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderAcceptance = $_GET['idOrderAcceptance'];
		$idOrderAcceptanceProduct = $_GET['idOrderAcceptanceProduct'];
		return GeradorDeDados::callWebService("orderAcceptanceProduct/get/$idOrderAcceptance/$idOrderAcceptanceProduct", []);
	}
}
include '../execute.php';

