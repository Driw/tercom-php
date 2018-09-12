<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductPrice']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Preço de Produto ID: <input type='text' name='idProductPrice'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductPrice = intval($_GET['idProductPrice']);
	return GeradorDeDados::callWebService("productPrice/get/$idProductPrice", []);
}
require_once 'execute.php';

?>