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
	<p>Família ID: <input type='text' name='idProductFamily'></p>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}

		$idProductFamily = intval($_GET['idProductFamily']);
		return GeradorDeDados::callWebService("productFamily/remove/$idProductFamily", []);
	}
}
include '../execute.php';

