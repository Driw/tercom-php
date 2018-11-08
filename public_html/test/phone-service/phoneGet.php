<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idPhone']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	Telefone ID: <input type="text" name="idPhone"><br>
	Relação com: <select name="relationship">
		<option value="customer">Telefone para Clientes</option>
	</select><br>
	Chave da Realação (ID): <input type="text" name="relationshipID"><br>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['relationshipID'];
		$idPhone = $_GET['idPhone'];

		return GeradorDeDados::callWebService("phone/get/$relationship/$idRelationship/$idPhone", []);
	}
}
include '../execute.php';

