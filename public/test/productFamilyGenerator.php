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
	Família: <input type='text' name='name'>
	<input type='submit' value='Continuar'>
</form>
<?php
		exit;
	}
	$parameters = [
		'name' => $_GET['name']
	];

	return GeradorDeDados::callWebService('productFamily/add', $parameters);
}
require_once 'execute.php';

?>