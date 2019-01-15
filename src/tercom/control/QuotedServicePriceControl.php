<?php

namespace tercom\control;

use tercom\dao\QuotedServicePriceDAO;
use tercom\entities\ServicePrice;
use tercom\entities\QuotedServicePrice;
use tercom\exceptions\QuotedServicePriceException;

/**
 *
 *
 * @see QuotedServicePriceDAO
 * @see QuotedServicePrice
 * @see ServicePrice
 *
 * @author Andrew
 */
class QuotedServicePriceControl extends GenericControl
{
	/**
	 *
	 * @var QuotedServicePriceDAO
	 */
	private $quotedServicePriceDAO;

	/**
	 *
	 */
	public function __construct()
	{
		$this->quotedServicePriceDAO = new QuotedServicePriceDAO();
	}

	/**
	 *
	 * @param ServicePrice $servicePrice
	 * @return QuotedServicePrice
	 */
	public function quote(ServicePrice $servicePrice): QuotedServicePrice
	{
		if (($servicePriceQuoted = $this->quotedServicePriceDAO->clone($servicePrice)) === null)
			throw QuotedServicePriceException::newInserted();

		return $servicePriceQuoted;
	}

	/**
	 *
	 * @param QuotedServicePrice $servicePriceQuoted
	 */
	public function remove(QuotedServicePrice $servicePriceQuoted): void
	{
		if (!$this->quotedServicePriceDAO->delete($servicePriceQuoted))
			throw QuotedServicePriceException::newDeleted();
	}

	/**
	 *
	 * @param int $idQuotedServicePrice
	 * @return QuotedServicePrice
	 */
	public function get(int $idQuotedServicePrice): QuotedServicePrice
	{
		if (($servicePriceQuoted = $this->quotedServicePriceDAO->select($idQuotedServicePrice)) === null)
			throw QuotedServicePriceException::newSelected();

		return $servicePriceQuoted;
	}
}

