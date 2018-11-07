<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['filter']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	Filtro: <select name='filter'>
		<option value="cnpj">CNPJ</option>
		<option value="companyName">Razão Social</option>
	</select>
	Valor: <input type='text' name='value'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = urlencode($_GET['value']);
		return GeradorDeDados::callWebService("customer/avaiable/$filter/$value", []);
	}
}
include '../execute.php';

?>