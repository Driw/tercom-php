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
	<p>Endereço de E-mail: <input type='text' name='email' required></p>
	<p>Senha: <input type='text' name='password' required></p>
	<p>usar Sessão (Navegador): <input type='checkbox' name='useSession' value='1' checked></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService("loginCustomer/login", $_GET);
	}
}
include '../execute.php';

