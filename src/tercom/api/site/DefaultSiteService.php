<?php

namespace tercom\api\site;

use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use dProject\MySQL\MySQL;
use tercom\control\AddressControl;
use tercom\control\PhoneControl;
use tercom\control\ProviderContactControl;
use tercom\control\ProviderControl;
use tercom\control\ServiceControl;
use tercom\control\ServicePriceControl;
use tercom\core\System;

/**
 * @see ApiServiceInterface
 * @author Andrew
 */

class DefaultSiteService extends ApiServiceInterface
{
	/**
	 * @var ProviderControl
	 */
	private $providerControl;
	/**
	 * @var ServiceControl
	 */
	private $serviceControl;
	/**
	 * @var ServicePriceControl
	 */
	private $servicePriceControl;
	/**
	 * @var AddressControl
	 */
	private $addressControl;

	/**
	 * Cria uma nova instância de um serviço para gerenciamento de stores dos produtos no sistema.
	 * @param ApiConnection $apiConnection conexão do sistema que realiza o chamado do serviço.
	 * @param string $apiname nome do serviço que está sendo informado através da conexão.
	 * @param ApiServiceInterface $parent serviço do qual solicitou o chamado.
	 */

	public function __construct(ApiConnection $apiConnection, string $apiname, ApiServiceInterface $parent)
	{
		parent::__construct($apiConnection, $apiname, $parent);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::execute()
	 */

	public function execute(): ApiResult
	{
		return $this->defaultExecute();
	}

	/**
	 * @param int|NULL $int
	 * @return int
	 */

	protected function parseNullToInt($int): int
	{
		return $int === null ? 0 : $int;
	}

	/**
	 * @return MySQL
	 */

	protected function getMySQL(): MySQL
	{
		return System::getWebConnection();
	}

	/**
	 * @return ProviderControl
	 */

	protected function newPhoneControl(): PhoneControl
	{
		return new PhoneControl($this->getMySQL());
	}

	/**
	 * @return ProviderControl
	 */

	protected function newProviderControl(): ProviderControl
	{
		return new ProviderControl($this->getMySQL());
	}

	/**
	 * @return ProviderControl
	 */

	protected function newProviderContactControl(): ProviderContactControl
	{
		return new ProviderContactControl($this->getMySQL());
	}

	/**
	 * @return ProviderControl
	 */
	protected function getProviderControl(): ProviderControl
	{
		return $this->providerControl === null ? ($this->providerControl = new ProviderControl($this->getMySQL())) : $this->providerControl;
	}

	/**
	 * @return ServiceControl
	 */
	protected function getServiceControl(): ServiceControl
	{
		return $this->serviceControl === null ? ($this->serviceControl = new ServiceControl($this->getMySQL())) : $this->serviceControl;
	}

	/**
	 * @return ServicePriceControl
	 */
	protected function getServicePriceControl(): ServicePriceControl
	{
		return $this->servicePriceControl === null ? ($this->servicePriceControl = new ServicePriceControl($this->getMySQL())) : $this->servicePriceControl;
	}

	/**
	 * @return AddressControl
	 */
	protected function getAddressControl(): AddressControl
	{
		return $this->addressControl === null ? ($this->addressControl = new AddressControl()) : $this->addressControl;
	}
}

