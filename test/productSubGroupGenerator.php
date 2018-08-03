<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['name']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Grupo ID: <input type='text' name='idProductGroup'>
	Subgrupo: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$parameters = [
		'name' => $_GET['name'],
		'idProductGroup' => $_GET['idProductGroup'],
	];

	return GeradorDeDados::callWebService('productSubGroup/add', $parameters);
}
require_once 'execute.php';

?>