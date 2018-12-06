<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProvider']))
		{
			header('Content-type: text/html');
			$pessoa = GeradorDeDados::genPessoa();
?>
<form method='get'>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>Nome: <input type='text' name='name' value='<?php echo $pessoa['nome']; ?>' required></p>
	<p>E-mail: <input type='text' name='email' value='<?php echo $pessoa['email']; ?>' required></p>
	<p>Cargo: <input type='text' name='position' value='<?php echo GeradorDeDados::genCargo(); ?>'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProvider = intval($_GET['idProvider']);

		return GeradorDeDados::callWebService("providerContact/add/$idProvider", $_GET);
	}
}
include_once '../execute.php';

?>