<?php

use tercom\GeradorDeDados;
use tercom\api\site\AddressService;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['relationship']))
		{
			header('Content-type: text/html');
			$empresa = GeradorDeDados::genEmpresa();

?>
<form method='get'>
	<p>Relação com: <select name='relationship'>
		<option value='customer'>Endereço para Clientes</option>
	</select></p>
	<p>Chave da Realação (ID): <input type='text' name='idRelationship'></p>
	<p>Estado: <input type='text' name='state' value='<?php echo $empresa['estado']; ?>' required></p>
	<p>Cidade: <input type='text' name='city' value='<?php echo $empresa['cidade']; ?>' required></p>
	<p>CEP: <input type='text' name='cep' value='<?php echo $empresa['cep']; ?>' required></p>
	<p>Bairro: <input type='text' name='neighborhood' value='<?php echo $empresa['bairro']; ?>' required></p>
	<p>Endereço: <input type='text' name='street' value='<?php echo $empresa['endereco']; ?>' required></p>
	<p>Número: <input type='text' name='number' value='<?php echo $empresa['numero']; ?>' required></p>
	<p>Complemento: <input type='text' name='complement' value='<?php echo $empresa['complemento']; ?>'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		$relationship = $_GET['relationship'];
		$idRelationship = $_GET['idRelationship'];

		return GeradorDeDados::callWebService("address/add/$relationship/$idRelationship", $_GET, true);
	}
}
include '../execute.php';

