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
	<p>Tercom Perfil ID: <input type='text' name='idTercomProfile' required></p>
	<p>Nome do Perfil: <input type='text' name='name'></p>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idTercomProfile = $_GET['idTercomProfile'];
		return GeradorDeDados::callWebService("tercomProfile/set/$idTercomProfile", $_GET);
	}
}
include '../execute.php';

