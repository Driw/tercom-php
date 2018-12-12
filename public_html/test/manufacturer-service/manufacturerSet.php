<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['fantasyName']))
		{
			header('Content-type: text/html');

			$empresa = GeradorDeDados::genEmpresa();
			?>
<form method='get'>
	<p>Fabricante ID: <input type='text' name='idManufacturer' required></p>
	<p>Nome Fantasia: <input type='text' name='fantasyName' value='<?php echo $empresa['nome']; ?>' required></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idManufacturer = $_GET['idManufacturer'];

		return GeradorDeDados::callWebService("manufacture/set/$idManufacturer", $_GET);
	}
}
include_once '../execute.php';

