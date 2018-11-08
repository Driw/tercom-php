<?php

use tercom\GeradorDeDados;
use tercom\api\site\PhoneService;
use tercom\entities\Phone;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['relationship']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
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
		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['relationshipID'];

		return GeradorDeDados::callWebService("phone/add/$relationship/$idRelationship", $parameters, true);
	}
}
include '../execute.php';

