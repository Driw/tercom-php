<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("customerEmployee/settings", []);
	}
}
include '../execute.php';

