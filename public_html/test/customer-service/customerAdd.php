<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (count($_GET) === 0)
		{
			header('Content-type: text/html');
			$empresa = GeradorDeDados::genEmpresa();
?>
<form method='get'>
	<p>Inscrição Estadual: <input type='text' name='stateRegistry' value='<?php echo $empresa['ie']; ?>' required></p>
	<p>CNPJ: <input type='text' name='cnpj' value='<?php echo $empresa['cnpj']; ?>' required></p>
	<p>Razão Social: <input type='text' name='companyName' value='<?php echo $empresa['nome']; ?>' required></p>
	<p>Nome Fantasia: <input type='text' name='fantasyName' value='<?php echo $empresa['nomeFantasia']; ?>' required></p>
	<p>Endereço de E-mail: <input type='text' name='email' value='<?php echo $empresa['email']; ?>' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('customer/add', $_GET);
	}
}
include '../execute.php';

