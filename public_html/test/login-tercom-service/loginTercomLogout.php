<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomEmployee']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Login ID: <input type='text' name='idLogin'></p>
	<p>Login Tercom ID: <input type='text' name='idTercomEmployee'></p>
	<p>Token: <input type='text' name='token'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService("loginTercom/logout", $_GET);
	}
}
include '../execute.php';

