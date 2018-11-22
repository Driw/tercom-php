<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("customerProfile/settings", []);
	}
}
include '../execute.php';

