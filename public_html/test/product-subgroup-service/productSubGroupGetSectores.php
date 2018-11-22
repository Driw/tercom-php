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
	<p>Subgrupo ID: <input type='text' name='idProductSubGroup'>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}
		$idProductSubGroup = intval($_GET['idProductSubGroup']);
		return GeradorDeDados::callWebService("productSubGroup/getCategories/$idProductSubGroup", []);
	}
}
include '../execute.php';

?>