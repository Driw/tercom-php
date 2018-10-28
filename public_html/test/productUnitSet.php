<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductUnit']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Unidade de Produto ID <input type="text" name="idProductUnit">
	Unidade: <input type='text' name='name'>
	Abreveação: <input type='text' name='shortName'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	$idProductUnit = intval($_GET['idProductUnit']);
	unset($_GET['idProductUnit']);

	return GeradorDeDados::callWebService("productUnit/set/$idProductUnit", $_GET);
}
require_once 'execute.php';

?>