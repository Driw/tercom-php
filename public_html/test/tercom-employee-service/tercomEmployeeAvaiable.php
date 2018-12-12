<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomEmployee']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Funcionário da TERCOM ID: <input type='text' name='idTercomEmployee'></p>
	<p>Filtro: <select name='filter'>
		<option value='cpf'>CPF</option>
		<option value='email'>Endereço de E-mail</option>
	</select></p>
	<p>Valor: <input type='text' name='value'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idTercomEmployee = $_GET['idTercomEmployee'];
		$filter = $_GET['filter'];
		$value = $_GET['value'];

		return GeradorDeDados::callWebService("tercomEmployee/avaiable/$filter/$value/$idTercomEmployee", []);
	}
}
include '../execute.php';

