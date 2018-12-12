<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductSubGroup']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Subgrupo ID: <input type='text' name='idProductSubGroup'></p>
	<p>Subgrupo: <input type='text' name='name'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idProductSubGroup = intval($_GET['idProductSubGroup']);
		return GeradorDeDados::callWebService("productSubGroup/set/$idProductSubGroup", $_GET, true);
	}
}
include '../execute.php';

