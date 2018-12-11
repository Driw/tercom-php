<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Tipo: <input type='text' name='name' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('productType/add', $_GET);
	}
}
include_once '../execute.php';

