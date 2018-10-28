<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductType']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Tipo de Produto ID <input type="text" name="idProductType">
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	$idProductType = intval($_GET['idProductType']);

	return GeradorDeDados::callWebService("productType/remove/$idProductType", []);
}
require_once 'execute.php';

?>