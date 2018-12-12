<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductFamily']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Família ID: <input type='text' name='idProductFamily' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProductFamily = intval($_GET['idProductFamily']);
		return GeradorDeDados::callWebService("productFamily/get/$idProductFamily", []);
	}
}
include '../execute.php';

