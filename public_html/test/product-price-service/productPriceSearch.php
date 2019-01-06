<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['filter']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p><input type='hidden' name='filter' value='name'></p>
	<p>Buscar por Nome: <input type='text' name='value'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<form method='get'>
	<p><input type='hidden' name='filter' value='product'></p>
	<p>Buscar por Produto ID: <input type='text' name='value'></p>
	<p>Fornecedor ID: <input type='text' name='idProvider'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = urlencode($_GET['value']);

		return GeradorDeDados::callWebService("productPrice/search/$filter/$value", $_GET);
	}
}
include_once '../execute.php';

