<?php

namespace tercom\api\site;

use dProject\MySQL\MySQL;
use dProject\restful\ApiConnection;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use tercom\core\System;
use tercom\control\CustomerAddressControl;
use tercom\control\CustomerControl;
use tercom\control\CustomerEmployeeControl;
use tercom\control\CustomerPermissionControl;
use tercom\control\CustomerPhoneControl;
use tercom\control\CustomerProfileControl;
use tercom\control\LoginCustomerControl;
use tercom\control\LoginTercomControl;
use tercom\control\PermissionControl;
use tercom\control\PhoneControl;
use tercom\control\ProductCategoryControl;
use tercom\control\ProviderContactControl;
use tercom\control\ProviderControl;
use tercom\control\ServiceControl;
use tercom\control\ServicePriceControl;
use tercom\control\TercomEmployeeControl;
use tercom\control\TercomPermissionControl;
use tercom\control\TercomProfileControl;

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
	 * @var ProductCategoryControl
	 */
	private $productCategoryControl;
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
	 * @var CustomerPermissionControl
	 */
	private $customerPermissionControl;
	/**
	 * @var CustomerEmployeeControl
	 */
	private $customerEmployeeControl;
	/**
	 * @var TercomProfileControl
	 */
	private $tercomProfileControl;
	/**
	 * @var TercomEmployeeControl
	 */
	private $tercomEmployeeControl;
	/**
	 * @var TercomPermissionControl
	 */
	private $tercomPermissionControl;
	/**
	 * @var LoginTercomControl
	 */
	private $loginTercomControl;
	/**
	 * @var LoginCustomerControl
	 */
	private $loginCustomerControl;

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
	 * @return ProductCategoryControl
	 */
	protected function getProductCategoryControl(): ProductCategoryControl
	{
		return $this->productCategoryControl === null ?
			($this->productCategoryControl = new ProductCategoryControl($this->getMySQL())) :
			$this->productCategoryControl;
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

	/**
	 * @return CustomerPermissionControl
	 */
	protected function getCustomerPermissionControl(): CustomerPermissionControl
	{
		return $this->customerPermissionControl === null ?
			($this->customerPermissionControl = new CustomerPermissionControl()) :
			$this->customerPermissionControl;
	}

	/**
	 * @return CustomerEmployeeControl
	 */
	protected function getCustomerEmployeeControl(): CustomerEmployeeControl
	{
		return $this->customerEmployeeControl === null ?
			($this->customerEmployeeControl = new CustomerEmployeeControl()) :
			$this->customerEmployeeControl;
	}

	/**
	 * @return TercomProfileControl
	 */
	protected function getTercomProfileControl(): TercomProfileControl
	{
		return $this->tercomProfileControl === null ?
			($this->tercomProfileControl = new TercomProfileControl()) :
			$this->tercomProfileControl;
	}

	/**
	 * @return TercomEmployeeControl
	 */
	protected function getTercomEmployeeControl(): TercomEmployeeControl
	{
		return $this->tercomEmployeeControl === null ?
			($this->tercomEmployeeControl = new TercomEmployeeControl()) :
			$this->tercomEmployeeControl;
	}

	/**
	 * @return TercomPermissionControl
	 */
	protected function getTercomPermissionControl(): TercomPermissionControl
	{
		return $this->tercomPermissionControl === null ?
			($this->tercomPermissionControl = new TercomPermissionControl()) :
			$this->tercomPermissionControl;
	}

	/**
	 * @return LoginTercomControl
	 */
	protected function getLoginTercomControl(): LoginTercomControl
	{
		return $this->loginTercomControl === null ?
			($this->loginTercomControl = new LoginTercomControl()) :
			$this->loginTercomControl;
	}

	/**
	 * @return LoginCustomerControl
	 */
	protected function getLoginCustomerControl(): LoginCustomerControl
	{
		return $this->loginCustomerControl === null ?
			($this->loginCustomerControl = new LoginCustomerControl()) :
			$this->loginCustomerControl;
	}
}

