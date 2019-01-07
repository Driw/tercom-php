<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['relationship']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	<p>Relação com: <select name="relationship">
		<option value="customer">Endereço para Clientes</option>
	</select></p>
	<p>Chave da Realação (ID): <input type="text" name="relationshipID"></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['relationshipID'];

		return GeradorDeDados::callWebService("address/getAll/$relationship/$idRelationship", []);
	}
}
include '../execute.php';

