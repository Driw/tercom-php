<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['filter']) && !isset($_GET['value']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Filtro: <select name="filter" required>
		<option value="fantasyName">Nome Fantasia</option>
	</select></p>
	<p>Valor: <input type='text' name='value' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = $_GET['value'];

		return GeradorDeDados::callWebService("manufacturer/search/$filter/$value", []);
	}
}
include_once '../execute.php';

