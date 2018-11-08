<?php

use tercom\GeradorDeDados;
use tercom\api\site\PhoneService;

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

		$telefone = GeradorDeDados::genTelefone();
		$parameters = [
			'ddd' => $telefone['ddd'],
			'number' => $telefone['numero'],
			'type' => $telefone['tipo'],
		];
		$idPhone = $_GET['idPhone'];
		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['relationshipID'];

		return GeradorDeDados::callWebService("phone/set/$relationship/$idRelationship/$idPhone", $parameters, true);
	}
}
include '../execute.php';

