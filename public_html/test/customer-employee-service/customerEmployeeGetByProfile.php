<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idCustomerProfile']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	<p>Perfil de Cliente ID: <input type='text' name='idCustomerProfile'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
		}

		$idCustomerProfile = $_GET['idCustomerProfile'];

		return GeradorDeDados::callWebService("customerEmployee/getByProfile/$idCustomerProfile", []);
	}
}
include '../execute.php';

