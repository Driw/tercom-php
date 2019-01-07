<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomEmployee']))
		{
			$pessoa = GeradorDeDados::genPessoa();
			$password = GeradorDeDados::genPassword(rand(MIN_PASSWORD_LEN, MAX_PASSWORD_LEN));

			header('Content-type: text/html');
?>
<form method='get'>
	<p>Funcionário da TERCOM ID: <input type='text' name='idTercomEmployee' required></p>
	<p>Perfil de Cliente ID: <input type='text' name='idTercomProfile'></p>
	<p>CPF: <input type='text' name='cpf' value='<?php echo $pessoa['cpf']; ?>'></p>
	<p>Nome: <input type='text' name='name' value='<?php echo $pessoa['nome']; ?>'></p>
	<p>Endereço de E-mail: <input type='text' name='email' value='<?php echo $pessoa['email']; ?>'></p>
	<p>Senha: <input type='text' name='password' value='<?php echo $password; ?>'></p>
	<p>Habilitado: <input type='checkbox' name='enabled' value="1" checked></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idTercomEmployee = $_GET['idTercomEmployee'];

		if (!isset($_GET['enable'])) $_GET['enable'] = true;

		return GeradorDeDados::callWebService("tercomEmployee/set/$idTercomEmployee", $_GET, true);
	}
}
include '../execute.php';

