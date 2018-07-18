<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']))
		return;
	$idProvider = intval($_GET['idProvider']);
	$pessoa = GeradorDeDados::genPessoa();
	$parameters = [
		'name' => $pessoa['nome'],
		'email' => $pessoa['email'],
		'position' => GeradorDeDados::genCargo(),
	];
	return GeradorDeDados::callWebService("providerContact/add/$idProvider", $parameters);
}
include_once 'execute.php';

?>