<?php

use tercom\GeradorDeDados;
use tercom\api\site\AddressService;

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
		<option value="customer">Endereço para Clientes</option>
	</select><br>
	Chave da Realação (ID): <input type="text" name="relationshipID"><br>
	<button type='submit'>Continuar</button>
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

