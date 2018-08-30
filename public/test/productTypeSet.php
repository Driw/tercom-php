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
	Tipo: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	$idProductType = intval($_GET['idProductType']);
	unset($_GET['idProductType']);

	return GeradorDeDados::callWebService("productType/set/$idProductType", $_GET);
}
require_once 'execute.php';

?>