<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productGroup/getAll', []);
	}
}
require_once '../execute.php';

?>