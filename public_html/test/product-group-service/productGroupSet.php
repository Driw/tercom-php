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
	<p>Grupo ID: <input type='text' name='idProductGroup' required></p>
	<p>Grupo: <input type='text' name='name'></p>
	<p><input type='submit' value='Continuar'></p>
</form>
<?php
			exit;
		}

		$idProductGroup = $_GET['idProductGroup'];
		return GeradorDeDados::callWebService("productGroup/set/$idProductGroup", $_GET, true);
	}
}
require_once '../execute.php';

?>