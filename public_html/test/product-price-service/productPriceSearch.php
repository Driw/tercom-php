<?php

use tercom\GeradorDeDados;

include_once '../include.php';
function testExecute()
{
	if (!isset($_GET['filter']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	<input type='hidden' name='filter' value='name'>
	Buscar por Nome: <input type='text' name='value'>
	<button type='submit'>Continuar</button>
</form>
<form method='get'>
	<input type='hidden' name='filter' value='product'>
	Buscar por Produto ID: <input type='text' name='value'>
	Fornecedor ID: <input type='text' name='idProvider'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$filter = $_GET['filter']; unset($_GET['filter']);
	$value = $_GET['value']; unset($_GET['value']);
	return GeradorDeDados::callWebService("productPrice/search/$filter/$value", $_GET);
}
include_once '../execute.php';

?>