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
	<p>*Funcionário TERCOM ID: <input type='text' name='idTercomEmployee'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idTercomEmployee = $_GET['idTercomEmployee'];
		return GeradorDeDados::callWebService("orderQuote/getByTercomEmployee/$idTercomEmployee", []);
	}
}
include '../execute.php';

