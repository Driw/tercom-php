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
	Unidade: <input type='text' name='name'>
	Abreveação: <input type='text' name='shortName'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	return GeradorDeDados::callWebService('productUnit/add', $_GET);
}
require_once 'execute.php';

?>