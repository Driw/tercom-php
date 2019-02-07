<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productSector/getAll', []);
	}
}
require_once '../execute.php';

?>