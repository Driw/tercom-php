<?php

use tercom\GeradorDeDados;

require_once 'include.php';
function testExecute()
{
	if (!isset($_GET['name']))
	{
		header('Content-type: text/html');
		?>
<form method='get'>
	Embalagem: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}

	return GeradorDeDados::callWebService('productPackage/add', $_GET);
}
require_once 'execute.php';

?>