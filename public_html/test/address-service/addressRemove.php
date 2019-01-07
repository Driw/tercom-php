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
	<p>Chave da Realação (ID): <input type="text" name="idRelationship"></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$idAddress = $_GET['idAddress'];
		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['idRelationship'];

		return GeradorDeDados::callWebService("address/remove/$relationship/$idRelationship/$idAddress", []);
	}
}
include '../execute.php';

