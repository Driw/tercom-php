<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idManufacture']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	<p>Fabricante ID: <input type='text' name='idManufacture' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idManufacture = intval($_GET['idManufacture']);

		return GeradorDeDados::callWebService("manufacturer/remove/$idManufacture", []);
	}
}
include_once '../execute.php';

