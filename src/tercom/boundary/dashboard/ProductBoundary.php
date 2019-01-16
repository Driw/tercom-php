<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\control\ProductControl;

/**
 * @see BoundaryManager
 * @author Andrew
 */

class ProductBoundary extends DefaultDashboardLoggedBoundary
{
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
		$baseTemplate = $this->newBaseTemplate();
		$result = new ApiTemplateResult();
		$result->add($baseTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onList(): ApiTemplateResult
	{
		$dashboardTemplate = $this->prepareInclude('ProductList');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @return ApiTemplateResult
	 */

	public function onSearch()
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProductSearch');
		$dashboardTemplate->setDataArray('FilterOption', $this->getFilterOptions());

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

	/**
	 * @return ApiTemplateResult
	 */

	public function onAdd(): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude('ProductAdd');

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
		$dashboardTemplate = $this->prepareInclude('ProductView');
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
		$dashboardTemplate = $this->prepareInclude('ProductPriceList');
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
		$dashboardTemplate = $this->prepareInclude('ProductPriceAdd');
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
		$dashboardTemplate = $this->prepareInclude('ProductPriceView');
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
		$dashboardTemplate = $this->prepareInclude('ProductPriceRemove');
		$dashboardTemplate->idProductPrice = $content->getParameters()->getInt('idProductPrice');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

