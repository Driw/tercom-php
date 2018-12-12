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
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		$idCustomer = $_GET['idCustomer'];
		return GeradorDeDados::callWebService("customerProfile/customer/$idCustomer", []);
	}
}
include '../execute.php';

