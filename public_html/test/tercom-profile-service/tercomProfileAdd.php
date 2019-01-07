<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Nome do Perfil: <input type='text' name='name' required></p>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel' required></p>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		return GeradorDeDados::callWebService('tercomProfile/add', $_GET);
	}
}
include '../execute.php';

