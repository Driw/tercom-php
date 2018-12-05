<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['email']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Endereço de E-mail: <input type='text' name='email'></p>
	<p>Senha: <input type='text' name='password'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService("loginTercom/login", $_GET);
	}
}
include '../execute.php';

