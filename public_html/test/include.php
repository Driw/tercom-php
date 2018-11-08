<?php

use dProject\Primitive\GlobalFunctions;

require_once(__DIR__.'/../../include.php');
require_once(__DIR__.'/../../vendor/autoload.php');
require_once(__DIR__.'/../../constants.php');
require_once(__DIR__.'/../../globalFunctions.php');

GlobalFunctions::init();

header('Content-type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
error_reporting(E_ALL);

