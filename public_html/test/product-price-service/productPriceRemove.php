<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductPrice']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Preço de Produto ID: <input type='text' name='idProductPrice'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idProductPrice = intval($_GET['idProductPrice']);
		return GeradorDeDados::callWebService("productPrice/remove/$idProductPrice", []);
	}
}
include_once '../execute.php';

