<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idOrderRequest']))
		{
			header('Content-type: text/html');

			?>
<form method='get'>
	<p>Pedido de Cotação ID: <input type='text' name='idOrderRequest' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idOrderRequest = $_GET['idOrderRequest'];

		return GeradorDeDados::callWebService("orderItemService/getAll/$idOrderRequest", []);
	}
}
include_once '../execute.php';

