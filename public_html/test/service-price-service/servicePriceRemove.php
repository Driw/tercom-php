<?php

use tercom\GeradorDeDados;

require_once '../include.php';
function testExecute()
{
	if (!isset($_GET['idServicePrice']))
	{
		header('Content-type: text/html');
?>
<form method='get'>
	<p>Preço de Serviço ID: <input type='text' name='idServicePrice'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
		exit;
	}
	$idServicePrice = intval($_GET['idServicePrice']);
	return GeradorDeDados::callWebService("servicePrice/remove/$idServicePrice", []);
}
require_once '../execute.php';

