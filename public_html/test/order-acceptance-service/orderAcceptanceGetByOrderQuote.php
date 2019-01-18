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
	<p>Cotação de Pedido ID: <input type='text' name='idOrderQuote'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderQuote = $_GET['idOrderQuote'];
		return GeradorDeDados::callWebService("orderAcceptance/getByOrderQuote/$idOrderQuote", []);
	}
}
include '../execute.php';

