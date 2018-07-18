<?php

use tercom\GeradorDeDados;

include_once 'include.php';

function testExecute()
{
	if (!isset($_GET['idProvider']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	<input type='text' name='idProvider'>
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
	$resultados = [];

	switch ($phoneType)
	{
		case 0:
			return [
				'commercial' => GeradorDeDados::callWebService("provider/removePhone/$idProvider/commercial", []),
				'otherphone' => GeradorDeDados::callWebService("provider/removePhone/$idProvider/otherphone", []),
			];
		case 1: return GeradorDeDados::callWebService("provider/removePhone/$idProvider/commercial", []);
		case 2: return GeradorDeDados::callWebService("provider/removePhone/$idProvider/otherphone", []);
	}

	return $resultados;
}
include_once 'execute.php';

?>