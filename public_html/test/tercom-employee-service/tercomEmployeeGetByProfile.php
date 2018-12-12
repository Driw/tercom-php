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
	<p>Perfil da TERCOM ID: <input type='text' name='idTercomProfile'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		$idTercomProfile = $_GET['idTercomProfile'];

		return GeradorDeDados::callWebService("tercomEmployee/getByProfile/$idTercomProfile", []);
	}
}
include '../execute.php';

