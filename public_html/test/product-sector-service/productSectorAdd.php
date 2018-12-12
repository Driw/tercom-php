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
	<p>Sub Grupo ID: <input type='text' name='idProductSubGroup'></p>
	<p>Setor: <input type='text' name='name'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('productSector/add', $_GET);
	}
}
include '../execute.php';

