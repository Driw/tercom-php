<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProduct']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Produto ID <input type="text" name="idProduct">
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	$idProduct = intval($_GET['idProduct']);

	return GeradorDeDados::callWebService("product/get/$idProduct", []);
}
require_once 'execute.php';

?>