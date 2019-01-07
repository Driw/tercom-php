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
	<p>Perfil de Cliente ID: <input type='text' name='idCustomerProfile' required></p>
	<p>Nome: <input type='text' name='name' value='<?php echo $pessoa['nome']; ?>' required></p>
	<p>Endereço de E-mail: <input type='text' name='email' value='<?php echo $pessoa['email']; ?>' required></p>
	<p>Senha: <input type='text' name='password' value='<?php echo $password; ?>' required></p>
	<p>Habilitado: <input type='checkbox' name='enabled' value="1" checked></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		if (!isset($_GET['enable'])) $_GET['enable'] = true;

		return GeradorDeDados::callWebService('customerEmployee/add', $_GET);
	}
}
include '../execute.php';

