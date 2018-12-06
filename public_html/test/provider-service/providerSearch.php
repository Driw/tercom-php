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
	<p>Buscar por Fornecedor: <input type='text' name='value'></p>
	<p>Filtro: <select name="filter">
		<option value="cnpj">CNPJ</option>
		<option value="fantasyName">Nome Fantasia</option>
	<p></select></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = $_GET['value'];

		return GeradorDeDados::callWebService("provider/search/$filter/$value", []);
	}
}
include_once '../execute.php';

?>