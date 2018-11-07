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

		$empresa = GeradorDeDados::genEmpresa();
		$parameters = [
			'state' => $empresa['estado'],
			'city' => $empresa['cidade'],
			'cep' => $empresa['cep'],
			'neighborhood' => $empresa['bairro'],
			'street' => $empresa['endereco'],
			'number' => $empresa['numero'],
			'complement' => $empresa['complemento'],
		];

		switch ($relationship = $_GET['relationship'])
		{
			case AddressService::RELATIONSHIP_CUSTOMER:
				$parameters['idCustomer'] = $_GET['relationshipID'];
				break;
		}

		return GeradorDeDados::callWebService("address/add/$relationship", $parameters, true);
	}
}
include '../execute.php';

