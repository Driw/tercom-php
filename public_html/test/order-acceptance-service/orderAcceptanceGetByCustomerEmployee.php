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
	<p>* Funcionário de Cliente ID: <input type='text' name='idCustomerEmployee'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idCustomerEmployee = $_GET['idCustomerEmployee'];
		return GeradorDeDados::callWebService("orderAcceptance/getByCustomerEmployee/$idCustomerEmployee", []);
	}
}
include '../execute.php';

