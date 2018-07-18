<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	$empresa = GeradorDeDados::genEmpresa();
	$pessoa = GeradorDeDados::genPessoa();
	$parameters = [
		'cnpj' => $empresa['cnpj'],
		'companyName' => $empresa['nome']. ' (CN)',
		'fantasyName' => $empresa['nome'],
		'spokesman' => $pessoa['nome'],
		'site' => $empresa['site'],
	];

	return $parameters;
	return GeradorDeDados::callWebService('provider/add', $parameters);
}
require_once 'execute.php';

?>