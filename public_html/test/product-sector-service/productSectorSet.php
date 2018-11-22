<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductSector']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Setor ID: <input type='text' name='idProductSector'></p>
	<p>Setor: <input type='text' name='name'></p>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}
		$idProductSector = intval($_GET['idProductSector']);
		return GeradorDeDados::callWebService("productSector/set/$idProductSector", $_GET, true);
	}
}
include '../execute.php';

?>