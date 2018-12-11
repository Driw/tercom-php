<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['filter']) || !isset($_GET['value']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Filtro: <select name='filter' required>
		<option value="name">Nome</option>
	</select></p>
	<p>Valor: <input type='text' name='value' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = urlencode($_GET['value']);

		return GeradorDeDados::callWebService("productPackage/search/$filter/$value", []);
	}
}
include_once '../execute.php';

