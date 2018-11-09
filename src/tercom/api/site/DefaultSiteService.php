<?php

namespace tercom\api\site;

use dProject\MySQL\MySQL;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use tercom\control\CustomerAddressControl;
use tercom\control\CustomerControl;
use tercom\control\CustomerPhoneControl;
use tercom\control\PermissionControl;
use tercom\control\PhoneControl;
use tercom\control\ProviderContactControl;
use tercom\control\ProviderControl;
use tercom\control\ServiceControl;
use tercom\control\ServicePriceControl;
use tercom\core\System;
use tercom\control\CustomerProfileControl;

/**
 * @see ApiServiceInterface
 * @author Andrew
 */

class DefaultSiteService extends ApiServiceInterface
{
	/**
	 * @var PhoneControl
	 */
	private $phoneControl;
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
	 * @var CustomerAddressControl
	 */
	private $customerAddressControl;
	/**
	 * @var CustomerControl
	 */
	private $customerControl;
	/**
	 * @var CustomerPhoneControl
	 */
	private $customerPhoneControl;
	/**
	 * @var PermissionControl
	 */
	private $permissionControl;
	/**
	 * @var CustomerProfileControl
	 */
	private $customerProfileControl;

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
	 * @return PhoneControl
	 */
	protected function getPhoneControl(): PhoneControl
	{
		return $this->phoneControl === null ? ($this->phoneControl = new PhoneControl()) : $this->phoneControl;
	}

	/**
	 * @return ProviderControl
	 */
	protected function getProviderControl(): ProviderControl
	{
		return $this->providerControl === null ?
			($this->providerControl = new ProviderControl($this->getMySQL())) :
			$this->providerControl;
	}

	/**
	 * @return ServiceControl
	 */
	protected function getServiceControl(): ServiceControl
	{
		return $this->serviceControl === null ?
			($this->serviceControl = new ServiceControl($this->getMySQL())) :
			$this->serviceControl;
	}

	/**
	 * @return ServicePriceControl
	 */
	protected function getServicePriceControl(): ServicePriceControl
	{
		return $this->servicePriceControl === null ?
			($this->servicePriceControl = new ServicePriceControl($this->getMySQL())) :
			$this->servicePriceControl;
	}

	/**
	 * @return CustomerAddressControl
	 */
	protected function getCustomerAddressControl(): CustomerAddressControl
	{
		return $this->customerAddressControl === null ?
			($this->customerAddressControl = new CustomerAddressControl()) :
			$this->customerAddressControl;
	}

	/**
	 * @return CustomerControl
	 */
	protected function getCustomerControl(): CustomerControl
	{
		return $this->customerControl === null ? ($this->customerControl = new CustomerControl()) : $this->customerControl;
	}

	/**
	 * @return CustomerPhoneControl
	 */
	protected function getCustomerPhoneControl(): CustomerPhoneControl
	{
		return $this->customerPhoneControl === null ?
			($this->customerPhoneControl = new CustomerPhoneControl()) :
			$this->customerPhoneControl;
	}

	/**
	 * @return PermissionControl
	 */
	protected function getPermissionControl(): PermissionControl
	{
		return $this->permissionControl === null ? ($this->permissionControl = new PermissionControl()) : $this->permissionControl;
	}

	/**
	 * @return CustomerProfileControl
	 */
	protected function getCustomerProfileControl(): CustomerProfileControl
	{
		return $this->customerProfileControl === null ?
			($this->customerProfileControl = new CustomerProfileControl()) :
			$this->customerProfileControl;
	}
}

