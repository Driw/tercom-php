<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductPackage']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<P>Embalagem de Produto ID <input type='text' name='idProductPackage' required></P>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProductPackage = intval($_GET['idProductPackage']);

		return GeradorDeDados::callWebService("productPackage/remove/$idProductPackage", []);
	}
}
include_once '../execute.php';

