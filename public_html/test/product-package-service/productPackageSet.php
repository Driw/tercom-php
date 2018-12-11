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
	<p>Embalagem de Produto ID <input type="text" name="idProductPackage" required></p>
	<p>Embalagem: <input type='text' name='name'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProductPackage = intval($_GET['idProductPackage']);

		return GeradorDeDados::callWebService("productPackage/set/$idProductPackage", $_GET, true);
	}
}
include_once '../execute.php';

