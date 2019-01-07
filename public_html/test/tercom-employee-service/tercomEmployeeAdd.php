<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomProfile']))
		{
			$pessoa = GeradorDeDados::genPessoa();
			$password = GeradorDeDados::genPassword(rand(MIN_PASSWORD_LEN, MAX_PASSWORD_LEN));

			header('Content-type: text/html');
?>
<form method='get'>
	<p>Perfil da TERCOM ID: <input type='text' name='idTercomProfile' required></p>
	<p>CPF: <input type='text' name='cpf' value='<?php echo $pessoa['cpf']; ?>' required></p>
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

		return GeradorDeDados::callWebService('tercomEmployee/add', $_GET);
	}
}
include '../execute.php';

