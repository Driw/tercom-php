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
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		$idTercomProfile = $_GET['idTercomProfile'];
		return GeradorDeDados::callWebService("tercomProfile/get/$idTercomProfile", []);
	}
}
include '../execute.php';

