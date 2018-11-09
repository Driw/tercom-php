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
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
		}

		$idCustomer = $_GET['idCustomer'];
		$assignmentLevel = $_GET['assignmentLevel'];

		return GeradorDeDados::callWebService("customerProfile/actions/$idCustomer/$assignmentLevel", []);
	}
}
include '../execute.php';

