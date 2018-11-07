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
	Cliente ID: <input type='text' name='idCustomer'>
	Inativo: <input type='checkbox' name='inactive' value='0'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$parameters = [
			'inactive' => isset($_GET['inactive']),
		];
		$idCustomer = $_GET['idCustomer'];

		return GeradorDeDados::callWebService("customer/setInactive/$idCustomer", $parameters);
	}
}
include '../execute.php';

