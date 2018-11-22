<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultCategorySettings;
use tercom\entities\ProductCategory;

/**
 * Serviço de Subgrupo de Produtos
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de subgrupo dos produtos.
 * Como serviço, oferece as possibilidades de acicionar subgrupo, atualizar subgrupo, obter subgrupo,
 * obter subgrupos de um grupo, remover subgrupo e procurar por subgrupo.
 *
 * @see DefaultSiteService
 * @author Andrew
 */
class ProductSubGroupService extends ProductCategoryService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryType()
	 */
	public function getProductCategoryType(): int
	{
		return ProductCategory::CATEGORY_SUBGROUP;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryParentType()
	 */
	public function getProductCategoryParentType(): int
	{
		return ProductCategory::CATEGORY_GROUP;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getIdFieldName()
	 */
	public function getIdFieldName(): string
	{
		return 'idProductSubgroup';
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getParentIdFieldName()
	 */
	public function getParentIdFieldName(): string
	{
		return 'idProductGroup';
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
