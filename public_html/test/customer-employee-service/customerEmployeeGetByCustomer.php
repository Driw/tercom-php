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
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
		}

		$idCustomer = $_GET['idCustomer'];

		return GeradorDeDados::callWebService("customerEmployee/getByCustomer/$idCustomer", []);
	}
}
include '../execute.php';

