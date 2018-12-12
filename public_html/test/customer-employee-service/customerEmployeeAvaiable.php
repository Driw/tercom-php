<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idCustomerEmployee']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Funcionário de Cliente ID: <input type='text' name='idCustomerEmployee'></p>
	<p>Filtro: <select name='filter'>
		<option value='email'>Endereço de E-mail</option>
	</select></p>
	<p>Valor: <input type='text' name='value'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idCustomerEmployee = $_GET['idCustomerEmployee'];
		$filter = $_GET['filter'];
		$value = $_GET['value'];

		return GeradorDeDados::callWebService("customerEmployee/avaiable/$filter/$value/$idCustomerEmployee", []);
	}
}
include '../execute.php';

