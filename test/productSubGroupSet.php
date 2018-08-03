<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductSubGroup']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Sub Grupo ID: <input type='text' name='idProductSubGroup'>
	Grupo ID: <input type='text' name='idProductGroup'>
	Sub Grupo: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductSubGroup = intval($_GET['idProductSubGroup']);
	$parameters = [
		'name' => $_GET['name'],
		'idProductGroup' => $_GET['idProductGroup'],
	];
	return GeradorDeDados::callWebService("productSubGroup/set/$idProductSubGroup", $parameters, true);
}
require_once 'execute.php';

?>