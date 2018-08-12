<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	$empresa = GeradorDeDados::genEmpresa();
	$parameters = [
		'fantasyName' => $empresa['nome'],
	];

	return GeradorDeDados::callWebService('manufacture/add', $parameters);
}
require_once 'execute.php';

?>