<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
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

		return GeradorDeDados::callWebService('address/add', $parameters, true);
	}
}
include '../execute.php';

?>