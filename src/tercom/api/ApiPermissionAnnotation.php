<?php

namespace tercom\api;

use dProject\restful\ApiAnnotation;
use dProject\restful\ApiContent;
use tercom\control\CustomerPermissionControl;
use tercom\control\LoginCustomerControl;
use tercom\control\LoginTercomControl;
use tercom\control\PermissionControl;
use tercom\control\TercomPermissionControl;
use tercom\TercomException;
use tercom\SessionVar;

/**
 * Anotação de API para Permissão
 *
 * Através dessa anotação a API poderá tratar e validar se um usuário possui permissão para executar uma ação no serviço.
 * Quando a ação for chamada a anotação será processada anteriormente validando conforme o usuário atualmente acessado.
 *
 * @author Andrew
 */
class ApiPermissionAnnotation extends ApiAnnotation
{
	/**
	 * @var string nome da ação que corresponde a ação executada no serviço.
	 */
	private $action;
	/**
	 * @var string nome do pacote que corresponde ao nome do serviço.
	 */
	private $packet;

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiAnnotation::prepare()
	 * @param ApiContent $content
	 */
	public function prepare($content)
	{
		parent::prepare($content);

		$parameters = $content->getParameters();
		$post = $content->getPost();

		$this->packet = $parameters->getString(-1);
		$this->action = $parameters->getString(0);

		if ($post->isSetted(SessionVar::LOGIN_CUSTOMER_ID))
			$this->validateCustomerEmployee();

		else if ($post->isSetted(SessionVar::LOGIN_TERCOM_ID))
			$this->validateTercomEmployee();

		else
			throw TercomException::newPermissionNotEnought();
	}

	/**
	 * Procedimento para validação do acesso de um usuário identificado como funcionário de cliente.
	 */
	private function validateCustomerEmployee(): void
	{
		$loginCustomerControl = new LoginCustomerControl();
		$loginCustomer = $loginCustomerControl->getCurrent();

		$permissionControl = new PermissionControl();
		$permission = $permissionControl->getAction($this->packet, $this->action);
		$customerProfile = $loginCustomer->getCustomerEmployee()->getCustomerProfile();

		$customerPermissionControl = new CustomerPermissionControl();
		$customerPermissionControl->verifyCustomerPermission($customerProfile, $permission);
	}

	/**
	 * Procedimento para validação do acesso de um usuário identificado como funcionário TERCOM.
	 */
	private function validateTercomEmployee(): void
	{
		$loginTercomControl = new LoginTercomControl();
		$loginTercom = $loginTercomControl->getCurrent();

		$permissionControl = new PermissionControl();
		$permission = $permissionControl->getAction($this->packet, $this->action);
		$tercomProfile = $loginTercom->getTercomEmployee()->getTercomProfile();

		$tercomPermissionControl = new TercomPermissionControl();
		$tercomPermissionControl->verifyTercomPermission($tercomProfile, $permission);
	}
}

