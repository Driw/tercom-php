<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['filter']) || !isset($_GET['value']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Filtro: <select name='filter'>
		<option value="name">Nome</option>
		<option value="family">Família do Produto</option>
		<option value="group">Grupo do Produto</option>
		<option value="subgroup">Subgroupo do Produto</option>
		<option value="sector">Setor</option>
	</select>
	Valor: <input type='text' name='value'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	$filter = $_GET['filter'];
	$value = urlencode($_GET['value']);

	return GeradorDeDados::callWebService("product/search/$filter/$value", []);
}
require_once 'execute.php';

?>