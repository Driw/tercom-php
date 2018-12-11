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
	<p>Tipo: <input type='text' name='name'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
		exit;
	}

		$idProductType = intval($_GET['idProductType']);
		unset($_GET['idProductType']);

		return GeradorDeDados::callWebService("productType/set/$idProductType", $_GET);
	}
}
include_once '../execute.php';

