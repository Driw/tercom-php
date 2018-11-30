<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomProfile']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Tercom Perfil ID: <input type='text' name='idTercomProfile'></p>
	<p>Nome do Perfil: <input type='text' name='name'></p>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		$idTercomProfile = $_GET['idTercomProfile'];
		return GeradorDeDados::callWebService("tercomProfile/set/$idTercomProfile", $_GET);
	}
}
include '../execute.php';

