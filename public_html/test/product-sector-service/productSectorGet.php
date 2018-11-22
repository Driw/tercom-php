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
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}
		$idProductSector = intval($_GET['idProductSector']);
		return GeradorDeDados::callWebService("productSector/get/$idProductSector", []);
	}
}
include '../execute.php';

?>