<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductGroup']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Grupo ID: <input type='text' name='idProductGroup'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductGroup = intval($_GET['idProductGroup']);
	return GeradorDeDados::callWebService("productGroup/getSubGroups/$idProductGroup", []);
}
require_once 'execute.php';

?>