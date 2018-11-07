<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("customer/getAll", []);
	}
}
include '../execute.php';

?>