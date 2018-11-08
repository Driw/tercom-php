<?php

use tercom\GeradorDeDados;
use tercom\api\site\AddressService;

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
	Chave da Realação (ID): <input type="text" name="idRelationship"><br>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$relationship = $_GET['relationship'];
		$idPhone = $_GET['idPhone'];
		$idRelationship = $_GET['idRelationship'];

		return GeradorDeDados::callWebService("phone/remove/$relationship/$idRelationship/$idPhone", []);
	}
}
include '../execute.php';

