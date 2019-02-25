<?php

use tercom\GeradorDeDados;
use tercom\entities\ProductCategory;

include_once '../include.php';
{
	function testExecute()
	{
		return GeradorDeDados::callWebService('product/settings', []);
	}
}
include_once '../execute.php';

