<?php

use dProject\Primitive\GlobalFunctions;

require_once(__DIR__.'/../../include.php');

GlobalFunctions::init();

header('Content-type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
error_reporting(E_ALL);

