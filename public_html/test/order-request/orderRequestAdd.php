<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['budget']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
<?php include '../loginCustomer.php' ?>
	<p>Orçamento: <input type='text' name='budget' placeholder='0.00' required></p>
	<p>Horário de Expiração: <input type='date' name='expiration'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('orderRequest/add', $_GET);
	}
}
include '../execute.php';

