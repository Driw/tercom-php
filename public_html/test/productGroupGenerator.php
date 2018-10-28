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
	Família ID: <input type='text' name='idProductFamily'>
	Grupo: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	return GeradorDeDados::callWebService('productGroup/add', $_POST);
}
require_once 'execute.php';

?>