<?php

use dProject\Primitive\Session;
use tercom\SessionVar;

$session = Session::getInstance();
$session->start();

?>
	<fieldset>
		<p>Funcionário de Cliente ID: <input type='text' name='idCustomerEmployee' value='<?php echo $session->getInt(SessionVar::LOGIN_CUSTOMER_ID, false) ?>' required></p>
		<p>Login ID: <input type='text' name='idLogin' value='<?php echo $session->getInt(SessionVar::LOGIN_ID, false) ?>' required></p>
		<p>Token: <input type='text' name='token' value='<?php echo $session->getString(SessionVar::LOGIN_TOKEN, false) ?>' required></p>
	</fieldset>