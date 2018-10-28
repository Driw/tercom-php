<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductPackage']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Embalagem de Produto ID <input type="text" name="idProductPackage">
	Embalagem: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	$idProductPackage = intval($_GET['idProductPackage']);
	unset($_GET['idProductPackage']);

	return GeradorDeDados::callWebService("productPackage/set/$idProductPackage", $_GET);
}
require_once 'execute.php';

?>