<?php

namespace tercom\boundary\dashboard;

use dProject\restful\template\ApiTemplateResult;
use dProject\restful\ApiContent;
use tercom\core\System;

/**
 * @see BoundaryManager
 * @author Andrew
 */
class CustomerProfileBoundary extends DefaultDashboardLoggedBoundary
{
	/**
	 * @var string
	 */
	public const BASE_PATH = 'CustomerProfile/';
	/**
	 * @var string
	 */
	public const PERMISSION_BASE_PATH = 'Permission/';

	/**
	 * {@inheritDoc}
	 * @see \dProject\restful\ApiServiceInterface::init()
	 */
	public function init()
	{
		parent::init();

		$this->setNavSideActive('customer', 'customerProfile');
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
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onList(ApiContent $content): ApiTemplateResult
	{
		if (!$content->getParameters()->isSetted('idCustomer'))
		{
			if ($this->isLoginTercom())
				$this->redirectRelative('customer/list');
			else
				$content->getParameters()->setInt('idCustomer', $this->getCustomerLogged()->getId());
		}

		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerProfileList');
		$dashboardTemplate->idCustomer = $this->getCustomerId($content);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onAdd(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerProfileAdd');
		$dashboardTemplate->idCustomer = $this->getCustomerId($content);

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomerProfile","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onView(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerProfileView');
		$dashboardTemplate->idCustomer = $this->getCustomerId($content);
		$dashboardTemplate->idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomerProfile","idCustomer"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onRemove(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::BASE_PATH. 'CustomerProfileRemove');
		$dashboardTemplate->idCustomer = $this->getCustomerId($content);
		$dashboardTemplate->idCustomerProfile = $content->getParameters()->getInt('idCustomerProfile');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}

	/**
	 * @ApiAnnotation({"params":["idCustomerProfile"]})
	 * @param ApiContent $content
	 * @return ApiTemplateResult
	 */
	public function onPermissions(ApiContent $content): ApiTemplateResult
	{
		$dashboardTemplate = $this->newBaseTemplate();
		$dashboardTemplate = $this->prepareInclude(self::PERMISSION_BASE_PATH. 'PermissionList');
		$dashboardTemplate->relationship = 'customer';
		$dashboardTemplate->idRelationship = $content->getParameters()->getInt('idCustomerProfile');

		$result = new ApiTemplateResult();
		$result->add($dashboardTemplate);

		return $result;
	}
}

