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
	<p>Nome Fantasia: <input type='text' name='fantasyName' value='<?php echo $empresa['nome']; ?>' required></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('manufacture/add', $_GET);
	}
}
include_once '../execute.php';

