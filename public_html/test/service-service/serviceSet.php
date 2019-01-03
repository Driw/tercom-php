<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Serviço ID: <input type='text' name='idService' required></p>
	<p>Nome: <input type='text' name='name'></p>
	<p>Descrição: <input type='text' name='description'></p>
	<p>Tags: <input type='text' name='tags'></p>
	<p>Inativo: <input type='checkbox' name='inactive' value='1'></p>
	<button type='submit'>Atualizar</button>
</form>
<?php
			exit;
		}

		$idService = $_GET['idService'];
		return GeradorDeDados::callWebService("service/set/$idService", $_GET);
	}
}
require_once '../execute.php';

