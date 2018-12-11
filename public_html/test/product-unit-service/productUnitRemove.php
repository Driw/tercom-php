<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductUnit']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Unidade de Produto ID <input type="text" name="idProductUnit"></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProductUnit = intval($_GET['idProductUnit']);

		return GeradorDeDados::callWebService("productUnit/remove/$idProductUnit", []);
	}
}
include_once '../execute.php';

