<?php

namespace tercom\api\site;

use dProject\MySQL\MySQL;
use dProject\restful\ApiConnection;
use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use dProject\restful\ApiServiceInterface;
use tercom\core\System;
use tercom\control\AddressControl;
use tercom\control\CustomerAddressControl;
use tercom\control\CustomerControl;
use tercom\control\CustomerEmployeeControl;
use tercom\control\CustomerPermissionControl;
use tercom\control\CustomerPhoneControl;
use tercom\control\CustomerProfileControl;
use tercom\control\LoginCustomerControl;
use tercom\control\LoginTercomControl;
use tercom\control\ManufacturerControl;
use tercom\control\OrderAcceptanceControl;
use tercom\control\OrderAcceptanceProductControl;
use tercom\control\OrderItemProductControl;
use tercom\control\OrderItemServiceControl;
use tercom\control\OrderQuoteControl;
use tercom\control\QuotedOrderProductControl;
use tercom\control\PermissionControl;
use tercom\control\PhoneControl;
use tercom\control\ProductCategoryControl;
use tercom\control\ProductControl;
use tercom\control\ProductPackageControl;
use tercom\control\ProductPriceControl;
use tercom\control\QuotedOrderServiceControl;
use tercom\control\QuotedProductPriceControl;
use tercom\control\ProductTypeControl;
use tercom\control\ProductUnitControl;
use tercom\control\ProviderContactControl;
use tercom\control\ProviderControl;
use tercom\control\QuotedServicePriceControl;
use tercom\control\ServiceControl;
use tercom\control\ServicePriceControl;
use tercom\control\TercomEmployeeControl;
use tercom\control\TercomPermissionControl;
use tercom\control\TercomProfileControl;
use tercom\control\OrderRequestControl;
use tercom\entities\CustomerEmployee;
use tercom\entities\LoginCustomer;
use tercom\entities\LoginTercom;
use tercom\entities\TercomEmployee;
use tercom\TercomException;

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
	 * @var ProviderContactControl
	 */
	private $providerContactControl;
	/**
	 * @var ManufacturerControl
	 */
	private $manufacturerControl;
	/**
	 * @var ProductCategoryControl
	 */
	private $productCategoryControl;
	/**
	 * @var ProductUnitControl
	 */
	private $productUnitControl;
	/**
	 * @var ProductControl
	 */
	private $productControl;
	/**
	 * @var ProductTypeControl
	 */
	private $productTypeControl;
	/**
	 * @var ProductPriceControl
	 */
	private $productPriceControl;
	/**
	 * @var ProductPackageControl
	 */
	private $productPackageControl;
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
	 * @var OrderRequestControl
	 */
	private $orderRequestControl;
	/**
	 * @var OrderItemProductControl
	 */
	private $orderItemProductControl;
	/**
	 * @var OrderItemServiceControl
	 */
	private $orderItemServiceControl;
	/**
	 * @var OrderQuoteControl
	 */
	private $orderQuoteControl;
	/**
	 * @var QuotedOrderProductControl
	 */
	private $quotedOrderProductControl;
	/**
	 * @var QuotedProductPriceControl
	 */
	private $quotedProductPriceControl;
	/**
	 * @var QuotedOrderServiceControl
	 */
	private $quotedOrderServiceControl;
	/**
	 * @var QuotedServicePriceControl
	 */
	private $quotedServicePriceControl;
	/**
	 * @var OrderAcceptanceControl
	 */
	private $orderAcceptanceControl;
	/**
	 * @var OrderAcceptanceProductControl
	 */
	private $orderAcceptanceProductControl;

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
	 *
	 * @param mixed $int
	 * @param int $default
	 * @return int
	 */
	protected function parseInt($int, int $default): int
	{
		return is_int($int) ? $int : $default;
	}

	/**
	 * @param bool $avaiable
	 * @return string
	 */
	protected function getMessageAvaiable(bool $avaiable)
	{
		return $avaiable ? 'disponível' : 'indisponível';
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
	 * Verifica se há o acesso de um funcionário TERCOM ou funcionário de cliente para obter seu nível de assinatura.
	 * @throws TercomException ocorre caso não haja nenhum tipo de usuário acessado (inesperado).
	 * @return int aquisição do nível de assinatura conforme usuário acessado.
	 */
	protected function getCurrentAssignmentLevel(): int
	{
		if ($this->getLoginTercomControl()->hasLogged())
			return $this->getLoginTercomControl()->getCurrent()->getTercomEmployee()->getTercomProfile()->getAssignmentLevel();

		if ($this->getLoginCustomerControl()->hasLogged())
			return $this->getLoginCustomerControl()->getCurrent()->getCustomerEmployee()->getCustomerProfile()->getAssignmentLevel();

		throw TercomException::newLoginUnexpected();
	}

	/**
	 * @return MySQL
	 */

	protected function getMySQL(): MySQL
	{
		return System::getWebConnection();
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
			($this->providerControl = new ProviderControl()) :
			$this->providerControl;
	}

	/**
	 * @return ProviderContactControl
	 */
	protected function getProviderContactControl(): ProviderContactControl
	{
		return $this->providerContactControl === null ?
			($this->providerContactControl = new ProviderContactControl()) :
			$this->providerContactControl;
	}

	/**
	 * @return ManufacturerControl
	 */
	protected function getManufacturerControl(): ManufacturerControl
	{
		return $this->manufacturerControl === null ?
			($this->manufacturerControl = new ManufacturerControl()) :
			$this->manufacturerControl;
	}

	/**
	 * @return ProductCategoryControl
	 */
	protected function getProductCategoryControl(): ProductCategoryControl
	{
		return $this->productCategoryControl === null ?
			($this->productCategoryControl = new ProductCategoryControl()) :
			$this->productCategoryControl;
	}

	/**
	 * @return ProductUnitControl
	 */
	protected function getProductUnitControl(): ProductUnitControl
	{
		return $this->productUnitControl === null ?
			($this->productUnitControl = new ProductUnitControl()) :
			$this->productUnitControl;
	}

	/**
	 * @return ProductControl
	 */
	protected function getProductControl(): ProductControl
	{
		return $this->productControl === null ?
			($this->productControl = new ProductControl()) :
			$this->productControl;
	}

	/**
	 * @return ProductPackageControl
	 */
	protected function getProductPackageControl(): ProductPackageControl
	{
		return $this->productPackageControl === null ?
			($this->productPackageControl = new ProductPackageControl()) :
			$this->productPackageControl;
	}

	/**
	 * @return ProductTypeControl
	 */
	protected function getProductTypeControl(): ProductTypeControl
	{
		return $this->productTypeControl === null ?
			($this->productTypeControl = new ProductTypeControl()) :
			$this->productTypeControl;
	}

	/**
	 * @return ProductPriceControl
	 */
	protected function getProductPriceControl(): ProductPriceControl
	{
		return $this->productPriceControl === null ?
			($this->productPriceControl = new ProductPriceControl()) :
			$this->productPriceControl;
	}

	/**
	 * @return ServiceControl
	 */
	protected function getServiceControl(): ServiceControl
	{
		return $this->serviceControl === null ?
			($this->serviceControl = new ServiceControl()) :
			$this->serviceControl;
	}

	/**
	 * @return ServicePriceControl
	 */
	protected function getServicePriceControl(): ServicePriceControl
	{
		return $this->servicePriceControl === null ?
			($this->servicePriceControl = new ServicePriceControl()) :
			$this->servicePriceControl;
	}

	/**
	 * @return AddressControl
	 */
	protected function getAddressControl(): AddressControl
	{
		return $this->addressControl === null ?
			($this->addressControl = new AddressControl()) :
			$this->addressControl;
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

	/**
	 * @return OrderRequestControl
	 */
	protected function getOrderRequestControl(): OrderRequestControl
	{
		return $this->orderRequestControl === null ?
			($this->orderRequestControl = new OrderRequestControl()) :
			$this->orderRequestControl;
	}

	/**
	 * @return OrderItemProductControl
	 */
	protected function getOrderItemProductControl(): OrderItemProductControl
	{
		return $this->orderItemProductControl === null ?
			($this->orderItemProductControl = new OrderItemProductControl()) :
			$this->orderItemProductControl;
	}

	/**
	 * @return OrderItemServiceControl
	 */
	protected function getOrderItemServiceControl(): OrderItemServiceControl
	{
		return $this->orderItemServiceControl === null ?
			($this->orderItemServiceControl = new OrderItemServiceControl()) :
			$this->orderItemServiceControl;
	}

	/**
	 * @return OrderQuoteControl
	 */
	protected function getOrderQuoteControl(): OrderQuoteControl
	{
		return $this->orderQuoteControl === null ?
			($this->orderQuoteControl = new OrderQuoteControl()) :
			$this->orderQuoteControl;
	}

	/**
	 * @return QuotedOrderProductControl
	 */
	protected function getQuotedOrderProductControl(): QuotedOrderProductControl
	{
		return $this->quotedOrderProductControl === null ?
			($this->quotedOrderProductControl = new QuotedOrderProductControl()) :
			$this->quotedOrderProductControl;
	}

	/**
	 * @return QuotedProductPriceControl
	 */
	protected function getQuotedProductPriceControl(): QuotedProductPriceControl
	{
		return $this->quotedProductPriceControl === null ?
			($this->quotedProductPriceControl = new QuotedProductPriceControl()) :
			$this->quotedProductPriceControl;
	}

	/**
	 * @return QuotedOrderServiceControl
	 */
	protected function getQuotedOrderServiceControl(): QuotedOrderServiceControl
	{
		return $this->quotedOrderServiceControl === null ?
			($this->quotedOrderServiceControl = new QuotedOrderServiceControl()) :
			$this->quotedOrderServiceControl;
	}

	/**
	 * @return QuotedServicePriceControl
	 */
	protected function getQuotedServicePriceControl(): QuotedServicePriceControl
	{
		return $this->quotedServicePriceControl === null ?
			($this->quotedServicePriceControl = new QuotedServicePriceControl()) :
			$this->quotedServicePriceControl;
	}

	/**
	 * @return OrderAcceptanceControl
	 */
	protected function getOrderAcceptanceControl(): OrderAcceptanceControl
	{
		return $this->orderAcceptanceControl === null ?
			($this->orderAcceptanceControl = new OrderAcceptanceControl()) :
			$this->orderAcceptanceControl;
	}

	/**
	 * @return OrderAcceptanceProductControl
	 */
	protected function getOrderAcceptanceProductControl(): OrderAcceptanceProductControl
	{
		return $this->orderAcceptanceProductControl === null ?
			($this->orderAcceptanceProductControl = new OrderAcceptanceProductControl()) :
			$this->orderAcceptanceProductControl;
	}

	/**
	 * @param ApiContent $content
	 * @return LoginCustomer
	 */
	protected function getCustomerEmployeeLogin(): LoginCustomer
	{
		return $this->getLoginCustomerControl()->getCurrent();
	}

	/**
	 * @return CustomerEmployee
	 */
	protected function getCustomerEmployee(): CustomerEmployee
	{
		return $this->getCustomerEmployeeLogin()->getCustomerEmployee();
	}

	/**
	 * @return CustomerEmployee|NULL
	 */
	protected function getCustomerEmployeeNull(): ?CustomerEmployee
	{
		return $this->getLoginCustomerControl()->hasLogged() ? $this->getCustomerEmployee() : null;
	}

	/**
	 * @param ApiContent $content
	 * @return LoginTercom
	 */
	protected function getTercomEmployeeLogin(): LoginTercom
	{
		return $this->getLoginTercomControl()->getCurrent();
	}

	/**
	 * @return TercomEmployee
	 */
	protected function getTercomEmployee(): TercomEmployee
	{
		return $this->getTercomEmployeeLogin()->getTercomEmployee();
	}
}

