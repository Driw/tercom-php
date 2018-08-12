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
	Sub Grupo ID: <input type='text' name='idProductSubGroup'>
	Setor: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$parameters = [
		'name' => $_GET['name'],
		'idProductSubGroup' => $_GET['idProductSubGroup'],
	];

	return GeradorDeDados::callWebService('productSector/add', $parameters);
}
require_once 'execute.php';

?>