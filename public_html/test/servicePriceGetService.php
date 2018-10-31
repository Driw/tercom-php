<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idService']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Serviço ID: <input type='text' name='idService'>
</form>
<?php
		exit;
	}
	$idService = intval($_GET['idService']);
	return GeradorDeDados::callWebService("servicePrice/getService/$idService", []);
}
require_once 'execute.php';

?>