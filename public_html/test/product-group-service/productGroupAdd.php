<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Família ID: <input type='text' name='idProductFamily' required></p>
	<p>Grupo: <input type='text' name='name' required></p>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('productGroup/add', $_GET);
	}
}
require_once '../execute.php';

?>