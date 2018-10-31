<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['name']))
	{
		header('Content-type: text/html');
?>
<form method='get'>
	Serviço ID: <input type='text' name='idService'><br>
	Fornecedor ID: <input type='text' name='idProvider'><br>
	Nome: <input type='text' name='name'><br>
	Preço: <input type='text' name='price'><br>
	Descrição Adicional: <input type='text' name='additionalDescription'><br>
	<button type="submit">Adicionar</button>
</form>
<?php
		exit;
	}

	return GeradorDeDados::callWebService('servicePrice/add', $_GET, true);
}
require_once 'execute.php';

?>