<?php

use tercom\GeradorDeDados;

require_once '../include.php';
function testExecute()
{
	if (!isset($_GET['idService']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Serviço ID: <input type='text' name='idService'>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
		exit;
	}
	$idService = intval($_GET['idService']);
	return GeradorDeDados::callWebService("servicePrice/getAll/$idService", []);
}
require_once '../execute.php';

