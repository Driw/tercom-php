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
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
		}

		$assignmentLevel = $_GET['assignmentLevel'];

		return GeradorDeDados::callWebService("customerEmployee/getByAssignmentLevel/$assignmentLevel", []);
	}
}
include '../execute.php';

