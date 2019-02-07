<?php

use tercom\GeradorDeDados;

require_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productSubgroup/getAll', []);
	}
}
require_once '../execute.php';

?>