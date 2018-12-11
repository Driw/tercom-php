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
	<p>Família: <input type='text' name='name'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		$idProductFamily = intval($_GET['idProductFamily']);

		return GeradorDeDados::callWebService("productFamily/set/$idProductFamily", $_GET, true);
	}
}
include '../execute.php';

