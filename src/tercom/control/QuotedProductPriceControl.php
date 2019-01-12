<?php

namespace tercom\control;

use tercom\dao\QuotedProductPriceDAO;
use tercom\entities\ProductPrice;
use tercom\entities\QuotedProductPrice;
use tercom\exceptions\QuotedProductPriceException;

/**
 *
 *
 * @see QuotedProductPriceDAO
 * @see QuotedProductPrice
 * @see ProductPrice
 *
 * @author Andrew
 */
class QuotedProductPriceControl extends GenericControl
{
	/**
	 *
	 * @var QuotedProductPriceDAO
	 */
	private $quotedProductPriceDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->quotedProductPriceDAO = new QuotedProductPriceDAO();
	}

	/**
	 *
	 * @param ProductPrice $productPrice
	 * @return QuotedProductPrice
	 */
	public function quote(ProductPrice $productPrice): QuotedProductPrice
	{
		if (($productPriceQuoted = $this->quotedProductPriceDAO->clone($productPrice)) === null)
			throw QuotedProductPriceException::newInserted();

		return $productPriceQuoted;
	}

	/**
	 *
	 * @param QuotedProductPrice $productPriceQuoted
	 */
	public function remove(QuotedProductPrice $productPriceQuoted): void
	{
		if (!$this->quotedProductPriceDAO->delete($productPriceQuoted))
			throw QuotedProductPriceException::newDeleted();
	}

	/**
	 *
	 * @param int $idQuotedProductPrice
	 * @return QuotedProductPrice
	 */
	public function get(int $idQuotedProductPrice): QuotedProductPrice
	{
		if (($productPriceQuoted = $this->quotedProductPriceDAO->select($idQuotedProductPrice)) === null)
			throw QuotedProductPriceException::newSelected();

		return $productPriceQuoted;
	}
}

