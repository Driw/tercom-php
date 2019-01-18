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
	<p>*Observação: <input type='text' name='observations'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idOrderAcceptance = $_GET['idOrderAcceptance'];
		return GeradorDeDados::callWebService("orderAcceptance/set/$idOrderAcceptance", $_GET);
	}
}
include '../execute.php';

