<?php

namespace tercom\api\site;

use tercom\api\ApiException;
use tercom\api\ApiResult;
use tercom\entities\ProductCategory;

/**
 *
 * @author Andrew
 */

class ApiResultCategory implements ApiResult
{
	/**
	 * @var string mensagem personalidade do resultado.
	 */
	private $message;
	/**
	 * @var ProductCategory categoria de produto.
	 */
	private $productCategory;

	/**
	 * @param ProductCategory $productCategories categoria de produto.
	 */

	public function setProductCategory(ProductCategory $productCategory)
	{
		$this->productCategory = $productCategory;
	}

	/**
	 * @param string $message mensagem personalidade do resultado.
	 */

	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\api\ApiResult::toApiArray()
	 */

	public function toApiArray():array
	{
		if ($this->productCategory === null)
			throw new ApiException('categoria de produto não definido no retorno');

		$array = $this->productCategory->toArray();

		if ($this->message !== null)
			$array['message'] = $this->message;

		return $array;
	}
}

