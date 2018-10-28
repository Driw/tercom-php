<?php

use tercom\GeradorDeDados;
use tercom\entities\Phone;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Fornecedor ID: <input type='text' name='idProvider'>
	<select name='phoneType'>
		<option value='0'>Ambos</option>
		<option value='1'>Comercial</option>
		<option value='2'>Secundário</option>
	</select>
	<button type='submit'>Continuar</button>
</form>
<?php
		exit;
	}
	$idProvider = intval($_GET['idProvider']);
	$phoneType = intval($_GET['phoneType']);
	$estado = GeradorDeDados::randArray(GeradorDeDados::getUFs());
	$parameters = [];

	if ($phoneType === 0 || $phoneType === 1)
	{
		$commercial = GeradorDeDados::genTelefone($estado);
		$parameters['commercial'] = [
			'ddd' => $commercial['ddd'],
			'number' => $commercial['numero'],
			'type' => Phone::TYPE_COMMERCIAL,
		];
	}

	if ($phoneType === 0 || $phoneType === 2)
	{
		$otherphone = GeradorDeDados::genCelular($estado);
		$parameters['otherphone'] = [
			'ddd' => $otherphone['ddd'],
			'number' => $otherphone['numero'],
			'type' => Phone::TYPE_PHONE,
		];
	}

	return GeradorDeDados::callWebService("provider/setPhones/$idProvider", $parameters);
}
include_once 'execute.php';

?>