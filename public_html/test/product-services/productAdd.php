<?php

use tercom\GeradorDeDados;

include_once '../include.php';
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
	<p>Utilidade: <input type='text' name='utility'></p>
	<p>Unidade de Produto ID: <input type='text' name='idProductUnit' required></p>
	<p>Família de Produto ID: <input type='text' name='idProductFamily'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('product/add', $_GET, true);
	}
}
include_once '../execute.php';

