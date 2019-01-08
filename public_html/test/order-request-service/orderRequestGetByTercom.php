<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (count($_GET) === 0)
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p><input type='text' name='idTercomEmployee'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		if (($idTercomEmployee = $_GET['idTercomEmployee']) === '')
			return GeradorDeDados::callWebService("orderRequest/getByTercom", []);
		else
			return GeradorDeDados::callWebService("orderRequest/getByTercom/$idTercomEmployee", []);
	}
}
include '../execute.php';

