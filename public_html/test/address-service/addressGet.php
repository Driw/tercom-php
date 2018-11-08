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
	Chave da Realação (ID): <input type="text" name="relationshipID"><br>
	<button type='submit'>Continuar</button>
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

