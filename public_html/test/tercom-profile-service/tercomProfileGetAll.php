<?php

use tercom\GeradorDeDados;

include '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('tercomProfile/getAll', []);
	}
}
include '../execute.php';

