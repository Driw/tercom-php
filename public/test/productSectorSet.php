<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductSector']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Setor ID: <input type='text' name='idProductSector'>
	Setor: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductSector = intval($_GET['idProductSector']);
	return GeradorDeDados::callWebService("productSector/set/$idProductSector", $_GET, true);
}
require_once 'execute.php';

?>