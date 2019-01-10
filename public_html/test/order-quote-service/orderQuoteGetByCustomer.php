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
	<p>*Cliente ID: <input type='text' name='idCustomer'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		if (empty($_GET['idCustomer']))
			return GeradorDeDados::callWebService("orderQuote/getByCustomer", []);

		$idCustomer = $_GET['idCustomer'];
		return GeradorDeDados::callWebService("orderQuote/getByCustomer/$idCustomer", []);
	}
}
include '../execute.php';

