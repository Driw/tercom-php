<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use dProject\restful\ApiResult;
use tercom\api\site\results\ApiResultCategorySettings;
use tercom\entities\ProductCategory;

/**
 * Serviço de Setores dos Produtos
 *
 * Este serviço realiza a comunicação do cliente para com o sistema em relação aos dados de setores dos produtos.
 * Como serviço, oferece as possibilidades de acicionar setor, atualizar setor, obter setor, remover setor e procurar por setor.
 *
 * @see ApiServiceInterface
 * @see ApiConnection
 * @author Andrew
 */

class ProductSectorService extends ProductCategoryService
{
	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryType()
	 */
	public function getProductCategoryType(): int
	{
		return ProductCategory::CATEGORY_SECTOR;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductCategoryParentType()
	 */
	public function getProductCategoryParentType(): int
	{
		return ProductCategory::CATEGORY_SUBGROUP;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getProductSubCategoryType()
	 */
	public function getProductSubCategoryType(): int
	{
		return ProductCategory::CATEGORY_NONE;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getIdFieldName()
	 */
	public function getIdFieldName(): string
	{
		return 'idProductSector';
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\site\ProductCategoryService::getParentIdFieldName()
	 */
	public function getParentIdFieldName(): string
	{
		return 'idProductSubGroup';
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

