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
	Sub Grupo: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductSubGroup = intval($_GET['idProductSubGroup']);
	return GeradorDeDados::callWebService("productSubGroup/set/$idProductSubGroup", $_GET, true);
}
require_once 'execute.php';

?>