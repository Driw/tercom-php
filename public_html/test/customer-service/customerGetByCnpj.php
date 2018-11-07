<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['cnpj']))
		{
			header('Content-type: text/html');
			?>
<form method='get'>
	CNPJ do Cliente: <input type='text' name='cnpj'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$cnpj = $_GET['cnpj'];
		return GeradorDeDados::callWebService("customer/getByCnpj/$cnpj", []);
	}
}
include '../execute.php';

?>