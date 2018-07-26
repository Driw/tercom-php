<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['idProvider']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Fornecedor ID: <input type='text' name='idProvider'>
</form>
<?php
		exit;
	}
	$idProvider = intval($_GET['idProvider']);
	return GeradorDeDados::callWebService("provider/get/$idProvider", []);
}
require_once 'execute.php';

?>