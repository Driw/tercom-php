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
	Família ID: <input type='text' name='idProductFamily'>
	Grupo: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductGroup = intval($_GET['idProductGroup']);
	return GeradorDeDados::callWebService("productGroup/set/$idProductGroup", $_GET, true);
}
require_once 'execute.php';

?>