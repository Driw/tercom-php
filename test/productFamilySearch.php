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
	Buscar por Família: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$name = $_GET['name'];
	return GeradorDeDados::callWebService("productFamily/search/$name", []);
}
require_once 'execute.php';

?>