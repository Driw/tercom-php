<?php

namespace tercom\entities;

use DateTime;
use dProject\Primitive\AdvancedObject;
use dProject\Primitive\FloatUtil;
use dProject\Primitive\IntegerUtil;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Preço de Produto
 *
 * Um produto pode possuir diversos presços e cada preço irá possuir algumas informações como:
 * fornecedor do qual oferece o produto no preço e quantidade informada, marca/fabricante (um produto tem várias marcas),
 * o tipo e embalagem deve ser do preço do produto já que um mesmo produto pode possuir vários.
 *
 * @see AdvancedObject
 * @author Andrew
 */

class ProductPrice extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres no nome do preço.
	 */
	public const MIN_NAME_LEN = 2;
	/**
	 * @var int quantidade máxima de caracteres no nome do preço.
	 */
	public const MAX_NAME_LEN = 64;
	/**
	 * @var int quantidade mínima do produto por preço.
	 */
	public const MIN_AMOUNT = 1;
	/**
	 * @var int quantidade máxima do produto por preço.
	 */
	public const MAX_AMOUNT = 9999;
	/**
	 * @var int valor mínimo permitido por preço de produto.
	 */
	public const MIN_PRICE = 0.01;
	/**
	 * @var int valor máximo permitido por preço de produto.
	 */
	public const MAX_PRICE = 99999.99;

	/**
	 * @var int código de identificação único do preço de produto.
	 */
	private $id;
	/**
	 * @var Product objeto do tipo produto do qual o preço pertence.
	 */
	private $product;
	/**
	 * @var Provider objeto do tipo fornecedor que oferece o preço.
	 */
	private $provider;
	/**
	 * @var Manufacturer objeto do tipo fabricante que oferece o preço.
	 */
	private $manufacturer;
	/**
	 * @var ProductPackage objeto do tipo embalagem de produto que oferece o preço.
	 */
	private $productPackage;
	/**
	 * @var ProductType objeto do tipo tipo de produto que oferece o preço.
	 */
	private $productType;
	/**
	 * @var string nome de exibição no preço do produto.
	 */
	private $name;
	/**
	 * @var int quantidade do produto oferecido pelo preço.
	 */
	private $amount;
	/**
	 * @var float preço total para aquisição do produto.
	 */
	private $price;
	/**
	 * @var DateTime horário da última atualização do preço de produto.
	 */
	private $lastUpdate;

	/**
	 *
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->amount = 0;
		$this->price = 0.0;
		$this->lastUpdate = new \DateTime();
	}

	/**
	 * @return int aquisição do código de identificação único do preço de produto.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do preço de produto.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return Product aquisição do objeto do tipo produto do qual o preço pertence.
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @return int aquisição do código de identificação do produto do preço ou zero se inválido.
	 */
	public function getProductId(): int
	{
		return $this->product === null ? 0 : $this->product->getId();
	}

	/**
	 * @param Product $product objeto do tipo produto do qual o preço pertence.
	 */
	public function setProduct(Product $product): void
	{
		$this->product = $product;
	}

	/**
	 * @return Provider aquisição do objeto do tipo fornecedor que oferece o preço.
	 */
	public function getProvider(): Provider
	{
		return $this->provider;
	}

	/**
	 * @return int aquisição do código de identificação do fornecedor ou zero se inválido.
	 */
	public function getProviderId(): int
	{
		return $this->provider === null ? 0 : $this->provider->getId();
	}

	/**
	 * @param Provider $providerProvider objeto do tipo fornecedor que oferece o preço.
	 */
	public function setProvider(Provider $provider): void
	{
		$this->provider = $provider;
	}

	/**
	 * @return Manufacturer|NULL aquisição do objeto do tipo fabricante que oferece o preço.
	 */
	public function getManufacturer(): ?Manufacturer
	{
		return $this->manufacturer;
	}

	/**
	 * @return int aquisição do código de identificação do fabricante ou zero se não especificado.
	 */
	public function getManufacturerId(): int
	{
		return $this->manufacturer === null ? 0 : $this->manufacturer->getId();
	}

	/**
	 * @param Manufacturer $manufacturer|NULL objeto do tipo fabricante que oferece o preço.
	 */
	public function setManufacturer(?Manufacturer $manufacturer): void
	{
		$this->manufacturer = $manufacturer;
	}

	/**
	 * @return ProductPackage aquisição do objeto do tipo embalagem de produto que oferece o preço.
	 */
	public function getProductPackage(): ProductPackage
	{
		return $this->productPackage;
	}

	/**
	 * @return int aquisição do código de identificação da embalagem de produto ou zero se inválido.
	 */
	public function getProductPackageId(): int
	{
		return $this->productPackage === null ? 0 : $this->productPackage->getID();
	}

	/**
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto que oferece o preço.
	 */
	public function setProductPackage(ProductPackage $productPackage): void
	{
		$this->productPackage = $productPackage;
	}

	/**
	 * @return ProductType|NULL aquisição do objeto do tipo tipo de produto que oferece o preço.
	 */
	public function getProductType(): ?ProductType
	{
		return $this->productType;
	}

	/**
	 * @return int aquisição do código de identificação do tipo de produto ou zero se não informado.
	 */
	public function getProductTypeId(): int
	{
		return $this->productType === null ? 0 : $this->productType->getID();
	}

	/**
	 * @param ProductType|NULL $productType objeto do tipo tipo de produto que oferece o preço.
	 */
	public function setProductType(?ProductType $productType): void
	{
		$this->productType = $productType;
	}

	/**
	 * @return string|NULL aquisição do nome de exibição no preço do produto.
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome de exibição no preço do produto.
	 */
	public function setName(?string $name): void
	{
		if (!empty($name) && !StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int aquisição da quantidade do produto oferecido pelo preço.
	 */
	public function getAmount(): int
	{
		return $this->amount;
	}

	/**
	 * @param int $amount quantidade do produto oferecido pelo preço.
	 */
	public function setAmount(int $amount): void
	{
		if (!IntegerUtil::inInterval($amount, self::MIN_AMOUNT, self::MAX_AMOUNT))
			throw new EntityParseException("quantidade deve ser de %d a %d (quantidade: $amount)", self::MIN_AMOUNT, self::MAX_AMOUNT);

		$this->amount = $amount;
	}

	/**
	 * @return int aquisição do preço total para aquisição do produto.
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @param int $price preço total para aquisição do produto.
	 */
	public function setPrice(float $price): void
	{
		if (!FloatUtil::inInterval($price, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new("preço deve ser de %.2f a %.2f (preço: $price)", self::MIN_PRICE, self::MAX_PRICE);

		$this->price = $price;
	}

	/**
	 * @return float aquisição do preço unitário do produto.
	 */
	public function getUnitPrice(): float
	{
		return $this->price / $this->amount;
	}

	/**
	 * @return DateTime aquisição do horário da última atualização do preço de produto.
	 */
	public function getLastUpdate(): DateTime
	{
		return $this->lastUpdate;
	}

	/**
	 * @param DateTime $datetime horário da última atualização do preço de produto.
	 */
	public function setLastUpdate(DateTime $lastUpdate): void
	{
		$this->lastUpdate = $lastUpdate;
	}

	/**
	 * O sistema por padrão considera o preço do produto desatualizado após 7 dias.
	 * @return bool true se for necessário atualizar o preço do produto ou false caso contrário.
	 */
	public function isNeedUpdate(): bool
	{
		$nextUpdateTimestamp = strtotime('+7 days', $this->lastUpdate->getTimestamp());

		return $nextUpdateTimestamp < time();
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
			'manufacturer' => Manufacturer::class,
			'productPackage' => ProductPackage::class,
			'productType' => ProductType::class,
			'name' => ObjectUtil::TYPE_STRING,
			'amount' => ObjectUtil::TYPE_INTEGER,
			'price' => ObjectUtil::TYPE_FLOAT,
			'lastUpdate' => ObjectUtil::TYPE_DATE,
		];
	}
}

