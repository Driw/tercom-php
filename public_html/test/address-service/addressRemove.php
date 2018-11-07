<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idAddress']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	Endereço ID: <input type='text' name='idAddress'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idAddress = $_GET['idAddress'];
		return GeradorDeDados::callWebService("address/remove/$idAddress", []);
	}
}
include '../execute.php';

?>