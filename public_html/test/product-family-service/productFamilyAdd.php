<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	Família: <input type='text' name='name' required>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('productFamily/add', $_GET);
	}
}
include '../execute.php';

