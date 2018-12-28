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
<?php include '../loginCustomer.php' ?>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idCustomerEmployee = isset($_GET['idCustomerEmployee']) ? $_GET['idCustomerEmployee'] : '';

		return GeradorDeDados::callWebService("orderRequest/getByCustomer", $_GET, true);
	}
}
include '../execute.php';

