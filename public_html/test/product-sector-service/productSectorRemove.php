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
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idProductSector = intval($_GET['idProductSector']);
		return GeradorDeDados::callWebService("productSector/remove/$idProductSector", []);
	}
}
include '../execute.php';

?>