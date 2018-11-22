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
	<p>Permissão ID: <input type='text' name='idPermission'></p>
	<p>Relação ID: <input type='text' name='idRelationship'></p>
	<p>Relação: <select name='relationship'>
		<option value="customer">Permissão para Cliente</option>
	</select></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		$idRelationship = $_GET['idRelationship'];
		$relationship = $_GET['relationship'];

		return GeradorDeDados::callWebService("managePermissions/add/$relationship/$idRelationship", $_GET);
	}
}
include '../execute.php';

