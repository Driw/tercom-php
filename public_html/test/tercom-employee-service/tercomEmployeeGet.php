<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomEmployee']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Funcionário da TERCOM ID: <input type='text' name='idTercomEmployee' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idTercomEmployee = $_GET['idTercomEmployee'];
		return GeradorDeDados::callWebService("tercomEmployee/get/$idTercomEmployee", []);
	}
}
include '../execute.php';

