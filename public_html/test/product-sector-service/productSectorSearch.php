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
	<p>Buscar por Setor: <input type='text' name='value'></p>
	<p>Filtro: <select name='filter'>
		<option value='name'>nome</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = urlencode($_GET['value']);

		return GeradorDeDados::callWebService("productSector/search/$filter/$value", []);
	}
}
include '../execute.php';

?>