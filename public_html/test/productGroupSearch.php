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
	Buscar por Grupo: <input type='text' name='value'>
	Filtro: <select name='filter'>
		<option value='name'>nome</option>
	</select>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$filter = $_GET['filter'];
	$value = $_GET['value'];
	return GeradorDeDados::callWebService("productGroup/search/$filter/$value", []);
}
require_once 'execute.php';

?>