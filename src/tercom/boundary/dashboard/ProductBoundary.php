<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\control\ProductControl;
use tercom\core\System;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ProductBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'Product/';
	/**
	 * @var string
	 */
	public const BASE_PATH_PRICE = 'ProductPrice/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('product');
	}

	/**
	 * {@inheritDoc}
	 * @see \dproject\restful\template\ApiTemplate::callIndex()
	 */
	public function callIndex()
	{
		return $this->onList(System::getApiConnection()->getContent());
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onSearch(ApiContent $content)
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductSearch');
		$dashboardTemplate->setDataArray('FilterOption', $this->getFilterOptions());

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductAdd');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'ProductView');
		$dashboardTemplate->idProduct = $content->getParameters()->getInt('idProduct');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onViewPrices(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ProductPriceList');
		$dashboardTemplate->idProduct = $content->getParameters()->getInt('idProduct');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProduct"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAddPrice(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ProductPriceAdd');
		$dashboardTemplate->idProduct = $content->getParameters()->getInt('idProduct');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onViewPrice(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ProductPriceView');
		$dashboardTemplate->idProductPrice = $content->getParameters()->getInt('idProductPrice');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idProductPrice"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemovePrice(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH_PRICE. 'ProductPriceRemove');
		$dashboardTemplate->idProductPrice = $content->getParameters()->getInt('idProductPrice');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return array
	 */
	private function getFilterOptions(): array
	{
		$providerFilters = ProductControl::getFilters();
		$filterOptions = [];

		foreach ($providerFilters as $value => $option)
			$filterOptions[] = [ 'Value' => $value, 'Option' => $option ];

		return $filterOptions;
	}
}

