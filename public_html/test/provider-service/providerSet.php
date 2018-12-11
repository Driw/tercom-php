<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['cnpj']))
		{
			header('Content-type: text/html');

			$empresa = GeradorDeDados::genEmpresa();
			$pessoa = GeradorDeDados::genPessoa();
?>
<form method='get'>
	<p>*Fornecedor ID: <input type='text' name='idProvider' required></p>
	<p>*CNPJ: <input type='text' name='cnpj' value='<?php echo $empresa['cnpj']; ?>'></p>
	<p>*Razão Social: <input type='text' name='companyName' value='<?php echo $empresa['nome']. ' (CN)'; ?>'></p>
	<p>*Nome Fantasia: <input type='text' name='fantasyName' value='<?php echo $empresa['nome']; ?>'></p>
	<p>*Representante: <input type='text' name='spokesman' value='<?php echo $pessoa['nome']; ?>'></p>
	<p>*Site: <input type='text' name='site' value='<?php echo $empresa['site']; ?>'></p>
	<input type='submit' value='Continuar'>
</form>
<?php
			exit;
		}

		$idProvider = intval($_GET['idProvider']);

		return GeradorDeDados::callWebService("provider/set/$idProvider", $_GET);
	}
}
include_once '../execute.php';

?>