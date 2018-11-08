<?php

use tercom\GeradorDeDados;
use tercom\api\site\AddressService;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idAddress']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	Endereço ID: <input type="text" name="idAddress"><br>
	Relação com: <select name="relationship">
		<option value="customer">Endereço para Clientes</option>
	</select><br>
	Chave da Realação (ID): <input type="text" name="idRelationship"><br>
	<button type='submit'>Continuar</button>
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

