<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['name']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Serviço ID: <input type='text' name='idService'></p>
	<p>Fornecedor ID: <input type='text' name='idProvider'></p>
	<p>Nome: <input type='text' name='name'></p>
	<p>Preço: <input type='text' name='price' required></p>
	<p>Descrição Adicional: <input type='text' name='additionalDescription'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}
		$idService = $_GET['idService'];
		return GeradorDeDados::callWebService("service/set/$idService", $_GET, true);
	}
}
require_once '../execute.php';

