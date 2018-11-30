<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['assignmentLevel']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	<p>Nível de Assinatura: <input type='text' name='assignmentLevel'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
		}

		$assignmentLevel = $_GET['assignmentLevel'];

		return GeradorDeDados::callWebService("tercomEmployee/getByAssignmentLevel/$assignmentLevel", []);
	}
}
include '../execute.php';

