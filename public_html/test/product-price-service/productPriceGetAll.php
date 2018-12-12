<?php

use tercom\GeradorDeDados;

include_once '../include.php';
function testExecute()
{
	if (!isset($_GET['idProduct']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Produto ID: <input type='text' name='idProduct'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProduct = intval($_GET['idProduct']);
	return GeradorDeDados::callWebService("productPrice/getAll/$idProduct", []);
}
include_once '../execute.php';

?>