<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['packet']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Permissão ID: <input type='text' name='idPermission'></p>
	<p>Pacote: <input type='text' name='packet'></p>
	<p>Ação: <input type='text' name='action'></p>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idPermission = $_GET['idPermission'];
		return GeradorDeDados::callWebService("permission/set/$idPermission", $_GET);
	}
}
include '../execute.php';

