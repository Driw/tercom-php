<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		$empresa = GeradorDeDados::genEmpresa();
		$parameters = [
			'stateRegistry' => $empresa['ie'],
			'cnpj' => $empresa['cnpj'],
			'companyName' => $empresa['nome'],
			'fantasyName' => $empresa['nomeFantasia'],
			'email' => $empresa['email'],
		];

		return GeradorDeDados::callWebService('customer/add', $parameters);
	}
}
include '../execute.php';

