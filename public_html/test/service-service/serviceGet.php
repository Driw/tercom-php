<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idService']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Serviço ID: <input type='text' name='idService' required></p>
</form>
<?php
			exit;
		}
		$idService = intval($_GET['idService']);
		return GeradorDeDados::callWebService("service/get/$idService", []);
	}
}
require_once '../execute.php';

