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
	<p>Cliente Perfil ID: <input type='text' name='idCustomerProfile'></p>
	<p>Permissão ID: <input type='text' name='idPermission'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
		}

		$idCustomerProfile = $_GET['idCustomerProfile'];
		$idPermission = $_GET['idPermission'];
		return GeradorDeDados::callWebService("managePermissions/remove/customer/$idCustomerProfile/$idPermission", $_GET);
	}
}
include '../execute.php';

