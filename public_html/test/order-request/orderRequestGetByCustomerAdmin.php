<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idCustomerEmployee']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Funcionário de Cliente ID: <input type='text' name='idCustomerEmployee' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idCustomerEmployee = $_GET['idCustomerEmployee'];

		return GeradorDeDados::callWebService("orderRequest/getByCustomer/$idCustomerEmployee", []);
	}
}
include '../execute.php';

