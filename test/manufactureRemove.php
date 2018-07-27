<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idManufacture']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Fabricante ID: <input type='text' name='idManufacture'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}

	$idManufacture = intval($_GET['idManufacture']);
	return GeradorDeDados::callWebService("manufacture/remove/$idManufacture", []);
}
include_once 'execute.php';

?>