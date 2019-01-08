<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (count($_GET) === 0)
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p><input type='text' name='idCustomerEmployee'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		if (($idCustomerEmployee = $_GET['idCustomerEmployee']) === '')
			return GeradorDeDados::callWebService("orderRequest/getByCustomer", []);
		else
			return GeradorDeDados::callWebService("orderRequest/getByCustomer/$idCustomerEmployee", []);
	}
}
include '../execute.php';

