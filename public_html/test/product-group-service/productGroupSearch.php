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
	<p>Buscar por Grupo: <input type='text' name='value'></p>
	<p>Filtro: <select name='filter'></p>
		<option value='name'>nome</option>
	</select>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = $_GET['value'];
		return GeradorDeDados::callWebService("productGroup/search/$filter/$value", []);
	}
}
include '../execute.php';

?>