<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idService']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Serviço ID: <input type='text' name='idService' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idService = intval($_GET['idService']);
		return GeradorDeDados::callWebService("provider/getByService/$idService", []);
	}
}
include_once '../execute.php';

?>