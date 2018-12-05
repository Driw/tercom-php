<?php

namespace tercom\control;

use tercom\entities\LoginCustomer;
use tercom\dao\LoginCustomerDAO;

/**
 * @author Andrew
 */
class LoginCustomerControl extends LoginControl
{
	/**
	 * @var LoginCustomerDAO
	 */
	private $loginCustomerDAO;
	/**
	 * @var CustomerEmployeeControl
	 */
	private $tercomEmployeeControl;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->loginCustomerDAO = new LoginCustomerDAO();
		$this->tercomEmployeeControl = new CustomerEmployeeControl();
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

			$tercomEmployee = $this->tercomEmployeeControl->getByEmail($email);

			if (password_verify($password, $tercomEmployee->getPassword()))
			{
				if (empty($userAgent) || !isset($_SERVER['REMOTE_ADDR']))
					throw new ControlException('informações insuficientes para acesso');

				$loginCustomer = new LoginCustomer();
				$loginCustomer->setCustomerEmployee($tercomEmployee);
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
}

