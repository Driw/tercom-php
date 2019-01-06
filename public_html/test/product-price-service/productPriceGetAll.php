<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProduct']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Produto ID: <input type='text' name='idProduct'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idProduct = intval($_GET['idProduct']);
		return GeradorDeDados::callWebService("productPrice/getAll/$idProduct", []);
	}
}
include_once '../execute.php';

