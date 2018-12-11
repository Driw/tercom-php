<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProvider']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Provider ID: <input type='text' name='idProvider'></p>
	<p><select name='phoneType'>
		<option value='0'>Ambos</option>
		<option value='1'>Comercial</option>
		<option value='2'>Secundário</option>
	</select></p>
	<p><button type='submit'>Continuar</button></p>
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
					'commercial' => GeradorDeDados::callWebService("provider/removeCommercial/$idProvider", []),
					'otherphone' => GeradorDeDados::callWebService("provider/removeOtherphone/$idProvider", []),
				];
			case 1: return GeradorDeDados::callWebService("provider/removeCommercial/$idProvider", []);
			case 2: return GeradorDeDados::callWebService("provider/removeOtherphone/$idProvider", []);
		}

		return $resultados;
	}
}
include_once '../execute.php';

?>