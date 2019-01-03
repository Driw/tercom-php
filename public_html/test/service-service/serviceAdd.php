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
	<p>Nome: <input type='text' name='name' required></p>
	<p>Descrição: <input type='text' name='description' required></p>
	<p>Tags: <input type='text' name='tags'></p>
	<p>Inativo: <input type='checkbox' name='inactive' value='1'></p>
	<p><button type='submit'>Adicionar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('service/add', $_GET);
	}
}
require_once '../execute.php';

