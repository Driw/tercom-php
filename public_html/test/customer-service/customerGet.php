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
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idCustomer = $_GET['idCustomer'];
		return GeradorDeDados::callWebService("customer/get/$idCustomer", []);
	}
}
include '../execute.php';

?>