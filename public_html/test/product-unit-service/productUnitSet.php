<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductUnit']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	<p>Unidade de Produto ID: <input type='text' name='idProductUnit' required></p>
	<p>Unidade: <input type='text' name='name'></p>
	<p>Abreviação: <input type='text' name='shortName'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idProductUnit = $_GET['idProductUnit'];

		return GeradorDeDados::callWebService("productUnit/set/$idProductUnit", $_GET, true);
	}
}
include_once '../execute.php';

