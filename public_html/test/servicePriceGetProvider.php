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
	Fornecedor ID: <input type='text' name='idProvider'>
	<button type="submit">Continuar</button>
</form>
<?php
		exit;
	}
	$idService = intval($_GET['idService']);
	$idProvider = intval($_GET['idProvider']);
	return GeradorDeDados::callWebService("servicePrice/getProvider/$idService/$idProvider", []);
}
require_once 'execute.php';

?>