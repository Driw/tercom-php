<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idCustomer']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	Cliente ID: <input type='text' name='idCustomer'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$empresa = GeradorDeDados::genEmpresa();
		$parameters = [
			'stateRegistry' => $empresa['ie'],
			'cnpj' => $empresa['cnpj'],
			'companyName' => $empresa['nome'],
			'fantasyName' => $empresa['nomeFantasia'],
			'email' => $empresa['email'],
		];
		$idCustomer = $_GET['idCustomer'];

		return GeradorDeDados::callWebService("customer/set/$idCustomer", $parameters);
	}
}
include '../execute.php';

