<?php

namespace tercom\control;

use dProject\Primitive\PostService;
use dProject\Primitive\Session;
use tercom\SessionVar;
use tercom\entities\LoginCustomer;
use tercom\dao\LoginCustomerDAO;

/**
 * @author Andrew
 */
class LoginCustomerControl extends LoginControl
{
	/**
	 * @var LoginCustomer
	 */
	private static $loginCustomer;
	/**
	 * @var LoginCustomerDAO
	 */
	private $loginCustomerDAO;
	/**
	 * @var CustomerEmployeeControl
	 */
	private $customerEmployeeControl;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->loginCustomerDAO = new LoginCustomerDAO();
		$this->customerEmployeeControl = new CustomerEmployeeControl();
	}

	/**
	 *
	 * @param string $email
	 * @param string $password
	 * @param string $userAgent
	 * @return LoginCustomer
	 */
	public function newLoginCustomer(string $email, string $password, string $userAgent): LoginCustomer
	{
		try {

			$customerEmployee = $this->customerEmployeeControl->getByEmail($email);

			if (password_verify($password, $customerEmployee->getPassword()))
			{
				if (empty($userAgent) || !isset($_SERVER['REMOTE_ADDR']))
					throw new ControlException('informações insuficientes para acesso');

				$loginCustomer = new LoginCustomer();
				$loginCustomer->setCustomerEmployee($customerEmployee);
				$loginCustomer->setBrowser($userAgent);
				$loginCustomer->setIpAddress($_SERVER['REMOTE_ADDR']);
				$loginCustomer->setLogout(false);
				return $loginCustomer;
			}

		} catch (ControlException $e) {
		}

		throw new ControlException('endereço de e-mail ou senha incorreta');
	}

	/**
	 *
	 * @param LoginCustomer $loginCustomer
	 * @throws ControlException
	 */
	public function add(LoginCustomer $loginCustomer): void
	{
		$this->loginCustomerDAO->beginTransaction();
		{
			$token = $this->newToken($loginCustomer->getCustomerEmployeeId(), $loginCustomer->getIpAddress());
			$loginCustomer->setToken($token);

			if (!$this->addLogin($loginCustomer) || !$this->loginCustomerDAO->insert($loginCustomer))
			{
				$this->loginCustomerDAO->rollback();
				throw new ControlException('não foi possível realizar o acesso');
			}

			$this->loginCustomerDAO->updateLogouts($loginCustomer);
		}
		$this->loginCustomerDAO->commit();
	}

	/**
	 *
	 * @param LoginCustomer $loginCustomer
	 * @return bool
	 */
	public function logout(LoginCustomer $loginCustomer): bool
	{
		if ($loginCustomer->isLogout())
			throw new ControlException('acesso já encerrado');

		return $this->setLogoff($loginCustomer);
	}

	/**
	 *
	 * @param LoginCustomer $loginCustomer
	 * @throws ControlException
	 */
	public function keepAlive(LoginCustomer $loginCustomer): void
	{
		if ($loginCustomer->isLogout())
			throw new ControlException('acesso já encerrado');

		if ($loginCustomer->getExpiration()->getTimestamp() <= time())
			throw new ControlException('tempo de acesso expirado');

		if (!$this->keepLoginAlive($loginCustomer))
			throw new ControlException('não foi possível manter seu acesso');
	}

	/**
	 *
	 * @param int $idLogin
	 * @param int $idCustomerEmployee
	 * @param string $token
	 * @throws ControlException
	 * @return LoginCustomer
	 */
	public function get(int $idLogin, int $idCustomerEmployee, string $token): LoginCustomer
	{
		if (($loginCustomer = $this->loginCustomerDAO->select($idLogin, $idCustomerEmployee)) === null)
			throw new ControlException('acesso inválido');

		if ($loginCustomer->getToken() !== $token)
			throw new ControlException('acesso negado');

		return $loginCustomer;
	}

	/**
	 * Procedimento para obter o acesso atual do funcionário de cliente considerando dados em post e session.
	 * A prioridade é considerar dados em post já que existe a possibilidade de comunicação sem session estabelecida.
	 * @throws ControlException apenas quando não for possível obter os dados de acesso.
	 * @return LoginCustomer aquisição do objeto do tipo acesso de funcionário de cliente.
	 */
	public function getCurrent(): LoginCustomer
	{
		if (self::$loginCustomer !== null)
			return self::$loginCustomer;

		$post = PostService::getInstance();

		if ($post->isSetted(SessionVar::LOGIN_CUSTOMER_ID) &&
			$post->isSetted(SessionVar::LOGIN_ID) &&
			$post->isSetted(SessionVar::LOGIN_TOKEN))
		{
			$idCustomerEmployee = $post->getInt(SessionVar::LOGIN_CUSTOMER_ID);
			$idLogin = $post->getInt(SessionVar::LOGIN_ID);
			$token = $post->getString(SessionVar::LOGIN_TOKEN);
		}

		else
		{
			$session = Session::getInstance();
			$session->start();

			if ($session->isSetted(SessionVar::LOGIN_CUSTOMER_ID) &&
				$session->isSetted(SessionVar::LOGIN_ID) &&
				$session->isSetted(SessionVar::LOGIN_TOKEN))
			{
				$idCustomerEmployee = $session->getInt(SessionVar::LOGIN_CUSTOMER_ID);
				$idLogin = $session->getInt(SessionVar::LOGIN_ID);
				$token = $session->getString(SessionVar::LOGIN_TOKEN);
			}

			else
				throw new ControlException('acesso não encontrado');
		}

		self::$loginCustomer = $this->get($idLogin, $idCustomerEmployee, $token);
		self::setTercomManagement(false);
		self::setCustomerLogged(self::$loginCustomer->getCustomerEmployee()->getCustomerProfile()->getCustomer());
		// FIXME verificar o tempo de acesso limite

		return self::$loginCustomer;
	}

	/**
	 * Verifica se há um acesso de funcionário de cliente efetuado no sistema.
	 * @return bool true se houver ou false caso contrário.
	 */
	public function hasLogged(): bool
	{
		return self::$loginCustomer !== null;
	}
}

