<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductType']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Tipo de Produto ID <input type='text' name='idProductType' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProductType = intval($_GET['idProductType']);

		return GeradorDeDados::callWebService("productType/remove/$idProductType", []);
	}
}
include_once '../execute.php';

