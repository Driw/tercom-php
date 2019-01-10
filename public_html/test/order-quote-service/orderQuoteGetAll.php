<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("orderQuote/getAll", []);
	}
}
include '../execute.php';

