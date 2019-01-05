<?php

use dProject\Primitive\Session;
use tercom\GeradorDeDados;
use tercom\SessionVar;

include '../include.php';
{
	function testExecute()
	{
		if (!isset($_GET['email']))
		{
			header('Content-type: text/html');
?>
<form method='get'>
	<p>Endereço de E-mail: <input type='text' name='email' required></p>
	<p>Senha: <input type='text' name='password' required></p>
	<p>usar Sessão (Navegador): <input type='checkbox' name='useSession' value='1' checked></p>
	<button type='submit'>Continuar</button>
</form>
<?php
			exit;
		}

		$call = GeradorDeDados::callWebService("loginTercom/login", $_GET);
		$response = $call['response'];

		if ($response['status'] === 1)
		{
			$resultAttributes = $response['result']['attributes'];
			$session = Session::getInstance();
			$session->start();
			$session->setString(SessionVar::LOGIN_TOKEN, $resultAttributes['token']);
			$session->setInt(SessionVar::LOGIN_ID, $resultAttributes['id']);
			$session->setInt(SessionVar::LOGIN_TERCOM_ID, $resultAttributes['tercomEmployee']['attributes']['id']);
		}

		return $call;
	}
}
include '../execute.php';

