<?php

namespace tercom\entities;

use DateTime;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;
use tercom\Entities\EntityParseException;
use dProject\Primitive\FloatUtil;
use dProject\Primitive\IntegerUtil;

/**
 * @see AdvancedObject
 * @author Andrew
 */

class ProductPrice extends AdvancedObject
{
	/**
	 * @var int
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int
	 */
	public const MAX_NAME_LEN = 64;
	/**
	 * @var int
	 */
	public const MIN_AMOUNT = 1;
	/**
	 * @var int
	 */
	public const MAX_AMOUNT = 9999;
	/**
	 * @var int
	 */
	public const MIN_PRICE = 0.01;
	/**
	 * @var int
	 */
	public const MAX_PRICE = 99999.99;

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var Product
	 */
	private $product;
	/**
	 * @var Provider
	 */
	private $provider;
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
		$this->product = new Product();
		$this->provider = new Provider();
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

	public function getID(): int
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
	 * @return Product
	 */

	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @param Product $product
	 */

	public function setProduct(Product $product)
	{
		$this->product = $product;
	}

	/**
	 * @return Provider
	 */

	public function getProvider(): Provider
	{
		return $this->provider;
	}

	/**
	 * @param Provider $providerProvider
	 */

	public function setProvider(Provider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * @return Manufacture
	 */

	public function getManufacture(): Manufacture
	{
		return $this->manufacture;
	}

	/**
	 * @param int $manufacture
	 */

	public function setManufacture(Manufacture $manufacture)
	{
		$this->manufacture = $manufacture;
	}

	/**
	 * @return ProductPackage
	 */

	public function getPackage(): ProductPackage
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

	public function getType(): ProductType
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
	 * @return string|NULL
	 */

	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */

	public function setName(?string $name)
	{
		if (!empty($name) && !StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int
	 */

	public function getAmount(): int
	{
		return $this->amount;
	}

	/**
	 * @param int $amount
	 */

	public function setAmount(int $amount)
	{
		if (!IntegerUtil::inInterval($amount, self::MIN_AMOUNT, self::MAX_AMOUNT))
			throw new EntityParseException("quantidade deve ser de %d a %d (quantidade: $amount)", self::MIN_AMOUNT, self::MAX_AMOUNT);

		$this->amount = $amount;
	}

	/**
	 * @return int
	 */

	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @param int $price
	 */

	public function setPrice(float $price)
	{
		if (!FloatUtil::inInterval($price, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new("preço deve ser de %.2f a %.2f (preço: $price)", self::MIN_PRICE, self::MAX_PRICE);

		$this->price = $price;
	}

	/**
	 * @return float
	 */

	public function getUnitPrice(): float
	{
		return $this->price / $this->amount;
	}

	/**
	 * @return DateTime
	 */

	public function getLastUpdate(): DateTime
	{
		return $this->lastUpdate;
	}

	/**
	 * @param DateTime $datetime
	 */

	public function setLastUpdate(DateTime $lastUpdate)
	{
		$this->lastUpdate = $lastUpdate;
	}

	/**
	 * @return bool
	 */

	public function isNeedUpdate(): bool
	{
		$nextUpdateTimestamp = strtotime('+7 days', $this->lastUpdate->getTimestamp());

		$nextUpdate = new DateTime();
		$nextUpdate->setTimestamp($nextUpdateTimestamp);

		return $nextUpdate->getTimestamp() < time();
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */

	public function getAttributeTypes(): array
	{
		return [
			'product' => Product::class,
			'provider' => Provider::class,
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

