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
	<p>Inativo: <input type='checkbox' name='inactive' value='1'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProduct = intval($_GET['idProduct']);
		$inactive = isset($_GET['inactive']) ? 1 : 0;

		return GeradorDeDados::callWebService("product/setInactive/$idProduct/$inactive", []);
	}
}
include_once '../execute.php';

