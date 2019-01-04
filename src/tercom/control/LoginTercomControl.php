<?php

namespace tercom\control;

use dProject\Primitive\PostService;
use dProject\Primitive\Session;
use tercom\entities\LoginTercom;
use tercom\dao\LoginTercomDAO;
use tercom\SessionVar;

/**
 * @author Andrew
 */
class LoginTercomControl extends LoginControl
{
	/**
	 * @var LoginTercomDAO
	 */
	private $loginTercomDAO;
	/**
	 * @var TercomEmployeeControl
	 */
	private $tercomEmployeeControl;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->loginTercomDAO = new LoginTercomDAO();
		$this->tercomEmployeeControl = new TercomEmployeeControl();
	}

	/**
	 *
	 * @param string $email
	 * @param string $password
	 * @param string $userAgent
	 * @return LoginTercom
	 */
	public function newLoginTercom(string $email, string $password, string $userAgent): LoginTercom
	{
		try {

			$tercomEmployee = $this->tercomEmployeeControl->getByEmail($email);

			if (password_verify($password, $tercomEmployee->getPassword()))
			{
				if (empty($userAgent) || !isset($_SERVER['REMOTE_ADDR']))
					throw new ControlException('informações insuficientes para acesso');

				$loginTercom = new LoginTercom();
				$loginTercom->setTercomEmployee($tercomEmployee);
				$loginTercom->setBrowser($userAgent);
				$loginTercom->setIpAddress($_SERVER['REMOTE_ADDR']);
				$loginTercom->setLogout(false);
				return $loginTercom;
			}

		} catch (ControlException $e) {
		}

		throw new ControlException('endereço de e-mail ou senha incorreta');
	}

	/**
	 *
	 * @param LoginTercom $loginTercom
	 * @throws ControlException
	 */
	public function add(LoginTercom $loginTercom): void
	{
		$this->loginTercomDAO->beginTransaction();
		{
			$token = $this->newToken($loginTercom->getTercomEmployeeId(), $loginTercom->getIpAddress());
			$loginTercom->setToken($token);

			if (!$this->addLogin($loginTercom) || !$this->loginTercomDAO->insert($loginTercom))
			{
				$this->loginTercomDAO->rollback();
				throw new ControlException('não foi possível realizar o acesso');
			}

			$this->loginTercomDAO->updateLogouts($loginTercom);
		}
		$this->loginTercomDAO->commit();
	}

	/**
	 *
	 * @param LoginTercom $loginTercom
	 * @return bool
	 */
	public function logout(LoginTercom $loginTercom): bool
	{
		if ($loginTercom->isLogout())
			throw new ControlException('acesso já encerrado');

		return $this->setLogoff($loginTercom);
	}

	/**
	 *
	 * @param LoginTercom $loginTercom
	 * @throws ControlException
	 */
	public function keepAlive(LoginTercom $loginTercom): void
	{
		if ($loginTercom->isLogout())
			throw new ControlException('acesso já encerrado');

		if ($loginTercom->getExpiration()->getTimestamp() <= time())
			throw new ControlException('tempo de acesso expirado');

		if (!$this->keepLoginAlive($loginTercom))
			throw new ControlException('não foi possível manter seu acesso');
	}

	/**
	 *
	 * @param int $idLogin
	 * @param int $idTercomEmployee
	 * @param string $token
	 * @throws ControlException
	 * @return LoginTercom
	 */
	public function get(int $idLogin, int $idTercomEmployee, string $token): LoginTercom
	{
		if (($loginTercom = $this->loginTercomDAO->select($idLogin, $idTercomEmployee)) === null)
			throw new ControlException('acesso inválido');

		if ($loginTercom->getToken() !== $token)
			throw new ControlException('acesso negado');

		return $loginTercom;
	}

	/**
	 * Procedimento para obter o acesso atual do funcionário TERCOM considerando dados em post e session.
	 * A prioridade é considerar dados em post já que existe a possibilidade de comunicação sem session estabelecida.
	 * @throws ControlException apenas quando não for possível obter os dados de acesso.
	 * @return LoginTercom aquisição do objeto do tipo acesso de funcionário TERCOM.
	 */
	public function getCurrent(): LoginTercom
	{
		$post = PostService::getInstance();

		if ($post->isSetted('idTercomEmployee') && $post->isSetted('idLogin') && $post->isSetted('token'))
		{
			$idTercomEmployee = $post->getInt('idTercomEmployee');
			$idLogin = $post->getInt('idLogin');
			$token = $post->getString('token');
		}

		else
		{
			$session = Session::getInstance();
			$session->start();

			if ($session->isSetted(SessionVar::LOGIN_TERCOM_ID) &&
				$session->isSetted(SessionVar::LOGIN_ID) &&
				$session->isSetted(SessionVar::LOGIN_TOKEN))
			{
				$idTercomEmployee = $post->getInt(SessionVar::LOGIN_TERCOM_ID);
				$idLogin = $post->getInt(SessionVar::LOGIN_ID);
				$token = $post->getString(SessionVar::LOGIN_TOKEN);
			}

			else
				throw new ControlException('acesso não encontrado');
		}

		$loginCustomer = $this->get($idLogin, $idTercomEmployee, $token);

		return $loginCustomer;
	}
}

