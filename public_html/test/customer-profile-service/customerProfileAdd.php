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
	<p>Nome do Perfil: <input type='text' name='name'></p>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		return GeradorDeDados::callWebService('customerProfile/add', $_GET);
	}
}
include '../execute.php';

