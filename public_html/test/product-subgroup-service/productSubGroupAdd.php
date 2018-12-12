<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['idProductGroup']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Grupo ID: <input type='text' name='idProductGroup'></p>
	<p>Subgrupo: <input type='text' name='name'></p>
	<p><button type='submit'>Continuar</button></p>
</form>
<?php
			exit;
		}

		return GeradorDeDados::callWebService('productSubGroup/add', $_GET);
	}
}
include '../execute.php';

?>