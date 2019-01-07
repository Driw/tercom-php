<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idAddress']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	<p>Endereço ID: <input type="text" name="idAddress"></p>
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
		$idAddress = $_GET['idAddress'];
		$idRelationship = $_GET['relationshipID'];

		return GeradorDeDados::callWebService("address/get/$relationship/$idRelationship/$idAddress", []);
	}
}
include '../execute.php';

