<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService("orderRequest/getAll", $_GET);
	}
}
include '../execute.php';

