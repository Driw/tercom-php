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
		<option value="tercom">Permissão para Tercom</option>
	</select></p>
	<button type='submit'>Continuar</button>
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

