<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['filter']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Buscar por Serviço: <input type='text' name='value' required></p>
	<p>Filtro: <select name='filter' required>
		<option value='name'>Nome</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$filter = $_GET['filter'];
		$value = htmlentities($_GET['value']);

		return GeradorDeDados::callWebService("service/search/$filter/$value", []);
	}
}
require_once '../execute.php';

?>