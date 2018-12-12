<?php

use tercom\GeradorDeDados;

include '../include.php';
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
	<p>CNPJ: <input type='text' name='cnpj' value='<?php echo $empresa['cnpj']; ?>' required></p>
	<p>Razão Social: <input type='text' name='companyName' value='<?php echo $empresa['nome']. ' (CN)'; ?>' required></p>
	<p>Nome Fantasia: <input type='text' name='fantasyName' value='<?php echo $empresa['nome']; ?>' required></p>
	<p>*Representante: <input type='text' name='spokesman' value='<?php echo $pessoa['nome']; ?>'></p>
	<p>*Site: <input type='text' name='site' value='<?php echo $empresa['site']; ?>'></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('provider/add', $_GET);
	}
}
include '../execute.php';

?>