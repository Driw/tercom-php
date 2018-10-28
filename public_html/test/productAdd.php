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
	<p>Nome: <input type='text' name='name'></p>
	<p>Descrição: <input type='text' name='description'></p>
	<p>Utilidade: <input type='text' name='utility'></p>
	<p>Unidade de Produto ID: <input type='text' name='idProductUnit'></p>
	<p>Família de Produto ID: <input type='text' name='idProductFamily'></p>
	<p>Grupo de Produto ID: <input type='text' name='idProductGroup'></p>
	<p>Subgrupo de Produto ID: <input type='text' name='idProductSubGroup'></p>
	<p>Setor de Produto ID: <input type='text' name='idProductSector'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	return GeradorDeDados::callWebService('product/add', $_GET, true);
}
require_once 'execute.php';

?>