<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['filter']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Filtro: <select name="filter">
		<option value="fantasyName">Nome Fantasia</option>
	</select>
	Valor: <input type='text' name='value'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}

	$filter = $_GET['filter'];
	$value = $_GET['value'];

	return GeradorDeDados::callWebService("manufacture/search/$filter/$value", []);
}
include_once 'execute.php';

?>