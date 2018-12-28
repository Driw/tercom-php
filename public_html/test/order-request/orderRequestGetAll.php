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

		return GeradorDeDados::callWebService("orderRequest/getAll", $_GET);
	}
}
include '../execute.php';

