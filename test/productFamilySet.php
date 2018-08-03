<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProductFamily']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Família ID: <input type='text' name='idProductFamily'>
	Família: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$idProductFamily = intval($_GET['idProductFamily']);
	return GeradorDeDados::callWebService("productFamily/set/$idProductFamily", $_GET);
}
require_once 'execute.php';

?>