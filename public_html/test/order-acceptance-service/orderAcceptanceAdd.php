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
	<p>Solicitação de Cotação de Pedido ID: <input type='text' name='idOrderQuote' required></p>
	<p>Endereço ID: <input type='text' name='idAddress' required></p>
	<p>* Observações: <input type='text' name='observations'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderQuote = $_GET['idOrderQuote'];
		return GeradorDeDados::callWebService("orderAcceptance/add/$idOrderQuote", $_GET);
	}
}
include '../execute.php';

