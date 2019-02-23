<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProduct']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Produto ID: <input type='text' name='idProduct' required></p>
	<p>Cliente Produto ID: <input type='text' name='idProductCustomer' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProduct = intval($_GET['idProduct']);
		$idProductCustomer = $_GET['idProductCustomer'];

		return GeradorDeDados::callWebService("product/setCustomerId/$idProduct/$idProductCustomer", []);
	}
}
include_once '../execute.php';

