<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idRelationship']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Relação ID: <input type='text' name='idRelationship'></p>
	<p>Permissão ID: <input type='text' name='idPermission'></p>
	<p>Relação: <select name='relationship'>
		<option value="customer">Permissão para Cliente</option>
		<option value="tercom">Permissão para Tercom</option>
	</select></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['idRelationship'];
		$idPermission = $_GET['idPermission'];

		return GeradorDeDados::callWebService("managePermissions/get/$relationship/$idRelationship/$idPermission", []);
	}
}
include '../execute.php';

