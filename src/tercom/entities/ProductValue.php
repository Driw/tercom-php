<?php

namespace tercom\entities;

use DateTime;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;

class ProductValue extends AdvancedObject
{
	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var int
	 */
	private $idProduct;
	/**
	 * @var int
	 */
	private $idProvider;
	/**
	 * @var Manufacture
	 */
	private $manufacture;
	/**
	 * @var ProductPackage
	 */
	private $package;
	/**
	 * @var ProductType
	 */
	private $type;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var int
	 */
	private $amount;
	/**
	 * @var float
	 */
	private $price;
	/**
	 * @var DateTime
	 */
	private $lastUpdate;

	/**
	 *
	 */

	public function __construct()
	{
		$this->id = 0;
		$this->idProduct = 0;
		$this->idProvider = 0;
		$this->type = new ProductType();
		$this->package = new ProductPackage();
		$this->manufacture = new Manufacture();
		$this->lastUpdate = new DateTime();
		$this->amount = 0;
		$this->price = 0.0;
	}

	/**
	 * @return int
	 */

	public function getID():?int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */

	public function setID(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @return int
	 */

	public function getIDProduct():int
	{
		return $this->idProduct;
	}

	/**
	 * @param int $idProduct
	 */

	public function setIDProduct(int $idProduct)
	{
		$this->idProduct = $idProduct;
	}

	/**
	 * @return int
	 */

	public function getIDProvider():int
	{
		return $this->idProvider;
	}

	/**
	 * @param int $idProviderProvider
	 */

	public function setIDProvider(int $idProvider)
	{
		$this->idProvider = $idProvider;
	}

	/**
	 * @return Manufacture
	 */

	public function getManufacture():Manufacture
	{
		return $this->manufacture;
	}

	/**
	 * @param int $manufacture
	 */

	public function setIDManufacture(Manufacture $manufacture)
	{
		$this->manufacture = $manufacture;
	}

	/**
	 * @return ProductPackage
	 */

	public function getPackage():ProductPackage
	{
		return $this->package;
	}

	/**
	 * @param ProductPackage $package
	 */

	public function setPackage(ProductPackage $package)
	{
		$this->package = $package;
	}

	/**
	 * @return ProductType
	 */

	public function getType():ProductType
	{
		return $this->type;
	}

	/**
	 * @param ProductType $type
	 */

	public function setType(ProductType $type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */

	public function getName():string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */

	public function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return int
	 */

	public function getAmount():int
	{
		return $this->amount;
	}

	/**
	 * @param int $amount
	 */

	public function setAmount(int $amount)
	{
		$this->amount = $amount;
	}

	/**
	 * @return int
	 */

	public function getPrice():float
	{
		return $this->price;
	}

	/**
	 * @param int $price
	 */

	public function setPrice(float $price)
	{
		$this->price = $price;
	}

	/**
	 * @return DateTime
	 */

	public function getLastUpdate():DateTime
	{
		return $this->lastUpdate;
	}

	/**
	 * @param int $lastUpdate
	 */

	public function setLastUpdate(int $lastUpdate)
	{
		$this->lastUpdate->setTimestamp($lastUpdate);
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes():array
	{
		return [
			'idProvider' => ObjectUtil::TYPE_INTEGER,
			'idProduct' => ObjectUtil::TYPE_INTEGER,
			'manufacture' => Manufacture::class,
			'package' => ProductPackage::class,
			'type' => ProductType::class,
			'name' => ObjectUtil::TYPE_STRING,
			'amount' => ObjectUtil::TYPE_INTEGER,
			'price' => ObjectUtil::TYPE_FLOAT,
			'lastUpdate' => ObjectUtil::TYPE_DATE,
		];
	}
}

