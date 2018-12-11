<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductGroup']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Grupo ID: <input type='text' name='idProductGroup' required></p>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}

		$idProductGroup = $_GET['idProductGroup'];
		return GeradorDeDados::callWebService("productGroup/getCategories/$idProductGroup", $_GET);
	}
}
require_once '../execute.php';

?>