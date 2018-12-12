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
	<p>Nome do Perfil: <input type='text' name='name'></p>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		$idCustomerProfile = $_GET['idCustomerProfile'];
		return GeradorDeDados::callWebService("customerProfile/set/$idCustomerProfile", $_GET);
	}
}
include '../execute.php';

