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
	<p>Serviço ID: <input type='text' name='idService' required></p>
	<p>Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>Nome: <input type='text' name='name'></p>
	<p>Preço: <input type='text' name='price' required></p>
	<p>Descrição Adicional: <input type='text' name='additionalDescription'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('servicePrice/add', $_GET, true);
	}
}
require_once '../execute.php';

