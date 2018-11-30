<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idTercomProfile']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	Perfil Tercom ID: <input type='text' name='idTercomProfile'>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$idTercomProfile = $_GET['idTercomProfile'];
		return GeradorDeDados::callWebService("tercomProfile/remove/$idTercomProfile", []);
	}
}
include '../execute.php';

?>