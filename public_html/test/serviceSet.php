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
	Nome: <input type='text' name='name'><br>
	Descrição: <input type='text' name='description'><br>
	Tags: <input type='text' name='tags'><br>
	Inativo: <input type='checkbox' name='inactive' value='1'><br>
	<button type="submit">Atualizar</button>
</form>
<?php
		exit;
	}
	$idService = $_GET['idService'];
	return GeradorDeDados::callWebService("service/set/$idService", $_GET);
}
require_once 'execute.php';

?>