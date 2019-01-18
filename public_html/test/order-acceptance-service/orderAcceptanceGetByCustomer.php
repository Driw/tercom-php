<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idCustomer']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Cliente ID: <input type='text' name='idCustomer'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		if (isset($_GET['idCustomer']))
		{
			$idCustomer = $_GET['idCustomer'];
			return GeradorDeDados::callWebService("orderAcceptance/getByCustomer/$idCustomer", []);
		}
		else
			return GeradorDeDados::callWebService("orderAcceptance/getByCustomer", []);
	}
}
include '../execute.php';

