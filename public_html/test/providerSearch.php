<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['filter']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Buscar por Fornecedor: <input type='text' name='value'>
	Filtro: <select name="filter">
		<option value="cnpj">CNPJ</option>
		<option value="fantasyName">Nome Fantasia</option>
	</select>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$filter = $_GET['filter'];
	$value = $_GET['value'];
	return GeradorDeDados::callWebService("provider/search/$filter/$value", []);
}
require_once 'execute.php';

?>