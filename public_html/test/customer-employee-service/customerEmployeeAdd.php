<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idCustomerProfile']))
		{
			$pessoa = GeradorDeDados::genPessoa();
			$password = GeradorDeDados::genPassword(rand(MIN_PASSWORD_LEN, MAX_PASSWORD_LEN));

			header('Content-type: text/html');
?>
<form method='get'>
	<p>Perfil de Cliente ID: <input type='text' name='idCustomerProfile'></p>
	<p>Nome: <input type='text' name='name' value='<?php echo $pessoa['nome']; ?>'></p>
	<p>Endereço de E-mail: <input type='text' name='email' value='<?php echo $pessoa['email']; ?>'></p>
	<p>Senha: <input type='text' name='password' value='<?php echo $password; ?>'></p>
	<p>Habilitado: <input type='checkbox' name='enabled' value="1" checked></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		if (!isset($_GET['enable'])) $_GET['enable'] = true;

		return GeradorDeDados::callWebService('customerEmployee/add', $_GET);
	}
}
include '../execute.php';

