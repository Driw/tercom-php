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
		<option value="category">Categoria do Produto</option>
		<option value="family">Família do Produto</option>
		<option value="group">Grupo do Produto</option>
		<option value="subgroup">Subgroupo do Produto</option>
		<option value="sector">Setor</option>
	</select></p>
	<p>Valor: <input type='text' name='value' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = urlencode($_GET['value']);

		return GeradorDeDados::callWebService("product/search/$filter/$value", []);
	}
}
include_once '../execute.php';

