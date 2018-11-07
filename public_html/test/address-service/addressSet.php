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
	Endereço ID: <input type='text' name='idAddress'>
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

		$idAddress = $_GET['idAddress'];
		return GeradorDeDados::callWebService("address/set/$idAddress", $parameters, true);
	}
}
include '../execute.php';

?>