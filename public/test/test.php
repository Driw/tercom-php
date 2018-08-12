<?php

use tercom\core\System;

include_once '../vendor/autoload.php';
include_once '../constants.php';

System::init();
$mysql = System::getWebConnection();
$query = $mysql->createQuery("DELETE FROM provider_contact WHERE id = 5");
$result = $query->execute();

var_dump($result->isSuccessful());
var_dump($result->getAffectedRows());

?>