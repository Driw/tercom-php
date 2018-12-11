<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultCategorySettings;
use tercom\entities\ProductCategory;

/**
 * Serviço de Família dos Produtos
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de família dos produtos.
 * Como serviço, oferece as possibilidades de acicionar família, atualizar família, obter família,
 * obter grupos da família, remover família e procurar por família.
 *
 * @see DefaultSiteService
 * @author Andrew
 */

class ProductFamilyService extends ProductCategoryService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryType()
	 */
	public function getProductCategoryType(): int
	{
		return ProductCategory::CATEGORY_FAMILY;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryParentType()
	 */
	public function getProductCategoryParentType(): int
	{
		return ProductCategory::CATEGORY_NONE;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductSubCategoryType()
	 */
	public function getProductSubCategoryType(): int
	{
		return ProductCategory::CATEGORY_GROUP;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getIdFieldName()
	 */
	public function getIdFieldName(): string
	{
		return 'idProductFamily';
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getParentIdFieldName()
	 */
	public function getParentIdFieldName(): string
	{
		return '';
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::actionSettings()
	 */
	public function actionSettings(ApiContent $content): ApiResult
	{
		return new ApiResultCategorySettings();
	}
}

