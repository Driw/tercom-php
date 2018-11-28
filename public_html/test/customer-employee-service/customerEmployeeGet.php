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
	<p>Funcionário de Cliente ID: <input type='text' name='idCustomerEmployee'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		$idCustomerEmployee = $_GET['idCustomerEmployee'];
		return GeradorDeDados::callWebService("customerEmployee/get/$idCustomerEmployee", []);
	}
}
include '../execute.php';

