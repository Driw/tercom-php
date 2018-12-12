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
	<p>Login ID: <input type='text' name='idLogin'></p>
	<p>Login Cliente ID: <input type='text' name='idCustomerEmployee'></p>
	<p>Token: <input type='text' name='token'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService("loginCustomer/logout", $_GET);
	}
}
include '../execute.php';

