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
	<input type='hidden' name='filter' value='name'>
	Buscar por Nome: <input type='text' name='value'>
	<input type='submit' value='Continuar'>
</form>
<form method='get'>
	<input type='hidden' name='filter' value='product'>
	Buscar por Produto ID: <input type='text' name='value'>
	Fornecedor ID: <input type='text' name='idProvider'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$filter = $_GET['filter']; unset($_GET['filter']);
	$value = $_GET['value']; unset($_GET['value']);
	return GeradorDeDados::callWebService("productPrice/search/$filter/$value", $_GET);
}
require_once 'execute.php';

?>