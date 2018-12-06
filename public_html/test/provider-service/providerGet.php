<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProvider']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idProvider = intval($_GET['idProvider']);
		return GeradorDeDados::callWebService("provider/get/$idProvider", []);
	}
}
include_once '../execute.php';

?>