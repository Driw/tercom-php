<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idPermission']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	Permissão ID: <input type='text' name='idPermission'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idPermission = $_GET['idPermission'];
		return GeradorDeDados::callWebService("permission/get/$idPermission", []);
	}
}
include '../execute.php';

?>