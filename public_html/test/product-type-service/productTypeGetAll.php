<?php

use tercom\GeradorDeDados;

include_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('productType/getAll', []);
	}
}
include_once '../execute.php';

