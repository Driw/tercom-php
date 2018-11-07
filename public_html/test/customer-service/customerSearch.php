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
		<option value="stateRegistry">Inscrição Estadual</option>
		<option value="cnpj">CNPJ</option>
		<option value="fantasyName">Nome Fantasia</option>
	</select>
	Valor: <input type='text' name='value'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = urlencode($_GET['value']);
		return GeradorDeDados::callWebService("customer/search/$filter/$value", []);
	}
}
include '../execute.php';

?>