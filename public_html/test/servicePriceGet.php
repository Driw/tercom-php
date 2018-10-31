<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idServicePrice']))
	{
		header('Content-type: text/html');
?>
<form method='get'>
	Preço de Serviço ID: <input type='text' name='idServicePrice'>
</form>
<?php
		exit;
	}
	$idServicePrice = intval($_GET['idServicePrice']);
	return GeradorDeDados::callWebService("servicePrice/get/$idServicePrice", []);
}
require_once 'execute.php';

?>