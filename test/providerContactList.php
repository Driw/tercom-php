<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Fornecedor ID: <input type='text' name='idProvider'>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProvider = intval($_GET['idProvider']);
	return GeradorDeDados::callWebService("providerContact/getContacts/$idProvider", []);
}
include_once 'execute.php';

?>