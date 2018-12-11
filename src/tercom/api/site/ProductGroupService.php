<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultCategorySettings;
use tercom\entities\ProductCategory;

/**
 * <h1>Serviço de Grupo dos Produtos</h1>
 *
 * <p>Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de grupo dos produtos.
 * Como serviço, oferece as possibilidades de acicionar grupo, atualizar grupo, obter grupo,
 * obter grupos de uma família, remover grupo e procurar por grupo.</p>
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProductGroupService extends ProductCategoryService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryType()
	 */
	public function getProductCategoryType(): int
	{
		return ProductCategory::CATEGORY_GROUP;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryParentType()
	 */
	public function getProductCategoryParentType(): int
	{
		return ProductCategory::CATEGORY_FAMILY;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductSubCategoryType()
	 */
	public function getProductSubCategoryType(): int
	{
		return ProductCategory::CATEGORY_SUBGROUP;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getIdFieldName()
	 */
	public function getIdFieldName(): string
	{
		return 'idProductGroup';
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getParentIdFieldName()
	 */
	public function getParentIdFieldName(): string
	{
		return 'idProductFamily';
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

