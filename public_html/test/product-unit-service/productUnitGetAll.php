<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productUnit/getAll', []);
	}
}
include_once '../execute.php';

