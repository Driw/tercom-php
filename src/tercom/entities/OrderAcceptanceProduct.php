<?php

namespace tercom\entities;

use dProject\Primitive\AdvancedObject;
use dProject\Primitive\FloatUtil;
use dProject\Primitive\IntegerUtil;
use dProject\Primitive\ObjectUtil;
use dProject\Primitive\StringUtil;

/**
 * Preço de Produto Aceito
 *
 * @see AdvancedObject
 * @see QuotedProductPrice
 * @see Product
 * @see ProductType
 * @see ProductPackage
 * @see Provider
 * @see Manufacturer
 *
 * @author Andrew
 */
class OrderAcceptanceProduct extends AdvancedObject
{
	/**
	 * @var int quantidade mínima de caracteres no nome do preço.
	 */
	public const MIN_NAME_LEN = QuotedProductPrice::MIN_NAME_LEN;
	/**
	 * @var int quantidade máxima de caracteres no nome do preço.
	 */
	public const MAX_NAME_LEN = QuotedProductPrice::MAX_NAME_LEN;
	/**
	 * @var int quantidade mínima do produto por preço.
	 */
	public const MIN_AMOUNT = QuotedProductPrice::MIN_AMOUNT;
	/**
	 * @var int quantidade máxima do produto por preço.
	 */
	public const MAX_AMOUNT = QuotedProductPrice::MAX_AMOUNT;
	/**
	 * @var int valor mínimo permitido por preço de produto cotado.
	 */
	public const MIN_PRICE = QuotedProductPrice::MIN_PRICE;
	/**
	 * @var int valor máximo permitido por preço de produto cotado.
	 */
	public const MAX_PRICE = QuotedProductPrice::MAX_PRICE;

	/**
	 * @var int código de identificação único do preço de produto aceito cotado.
	 */
	private $id;
	/**
	 * @var int código de identificação do preço de produto contado.
	 */
	private $idQuotedProductPrice;
	/**
	 * @var Product objeto do tipo produto do qual o preço pertence.
	 */
	private $product;
	/**
	 * @var Provider objeto do tipo fornecedor do produto com preço aceito oferecido.
	 */
	private $provider;
	/**
	 * @var Manufacturer objeto do tipo fabricante do produto com preço aceito oferecido.
	 */
	private $manufacturer;
	/**
	 * @var ProductPackage objeto do tipo embalagem de produto com preço aceito oferecido.
	 */
	private $productPackage;
	/**
	 * @var ProductType objeto do tipo tipo de produto com preço aceito oferecido.
	 */
	private $productType;
	/**
	 * @var string nome de exibição no preço do produto aceito.
	 */
	private $name;
	/**
	 * @var int quantidade do produto oferecido pelo preço aceito.
	 */
	private $amount;
	/**
	 * @var float quantidade solicitada do item em questão.
	 */
	private $amountRequest;
	/**
	 * @var float preço total para aquisição do produto.
	 */
	private $price;
	/**
	 * @var float preço total para aquisição da quantidade do produto.
	 */
	private $subprice;
	/**
	 * @var string observações referentes ao preço de produto aceito.
	 */
	private $observations;
	/**
	 * @var \DateTime horário da última atualização do preço de produto cotado.
	 */
	private $lastUpdate;

	/**
	 * Cria uma nova instância de um preço de produto aceito.
	 */
	public function __construct()
	{
		$this->id = 0;
		$this->amount = 0;
		$this->price = 0.0;
		$this->lastUpdate = new \DateTime();
	}

	/**
	 * @return int aquisição do código de identificação único do preço de produto aceito cotado.
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id código de identificação único do preço de produto aceito cotado.
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return int aquisição do código de identificação do preço de produto contado.
	 */
	public function getIdQuotedProductPrice(): int
	{
		return $this->idQuotedProductPrice;
	}

	/**
	 * @param int $idQuotedProductPrice código de identificação do preço de produto contado.
	 */
	public function setIdQuotedProductPrice(int $idQuotedProductPrice): void
	{
		$this->idQuotedProductPrice = $idQuotedProductPrice;
	}

	/**
	 * @param QuotedProductPrice $quotedProductPrice objeto do tipo preço de produto baseado.
	 */
	public function setQuotedProductPrice(QuotedProductPrice $quotedProductPrice): void
	{
		$this->productPrice = $quotedProductPrice;
		$array = $quotedProductPrice->toArray(true);
		$array['idQuotedProductPrice'] = $array['id'];
		unset($array['id']);

		$this->fromArray($array);
	}

	/**
	 * @return float aquisição da quantidade solicitada do item em questão.
	 */
	public function getAmountRequest(): float
	{
		return $this->amountRequest;
	}

	/**
	 * @param float $amount quantidade solicitada do item em questão.
	 */
	public function setAmountRequest(float $amountRequest): void
	{
		$this->amountRequest = $amountRequest;
		$this->updateSubPrice();
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
	 * @return Provider aquisição do objeto do tipo fornecedor do produto com preço aceito oferecido.
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
	 * @param Provider $providerProvider objeto do tipo fornecedor do produto com preço aceito oferecido.
	 */
	public function setProvider(Provider $provider): void
	{
		$this->provider = $provider;
	}

	/**
	 * @return Manufacturer aquisição do objeto do tipo fabricante do produto com preço aceito oferecido.
	 */
	public function getManufacturer(): Manufacturer
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
	 * @param int $manufacturer objeto do tipo fabricante do produto com preço aceito oferecido.
	 */
	public function setManufacturer(Manufacturer $manufacturer): void
	{
		$this->manufacturer = $manufacturer;
	}

	/**
	 * @return ProductPackage aquisição do objeto do tipo embalagem de produto com preço aceito oferecido.
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
	 * @param ProductPackage $productPackage objeto do tipo embalagem de produto com preço aceito oferecido.
	 */
	public function setProductPackage(ProductPackage $productPackage): void
	{
		$this->productPackage = $productPackage;
	}

	/**
	 * @return ProductType objeto do tipo tipo de produto com preço aceito oferecido.
	 */
	public function getProductType(): ProductType
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
	 * @param ProductType $productType objeto do tipo tipo de produto com preço aceito oferecido.
	 */
	public function setProductType(ProductType $productType): void
	{
		$this->productType = $productType;
	}

	/**
	 * @return string|NULL aquisição do nome de exibição no preço do produto aceito.
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name nome de exibição no preço do produto aceito.
	 */
	public function setName(?string $name): void
	{
		if (!empty($name) && !StringUtil::hasBetweenLength($name, self::MIN_NAME_LEN, self::MAX_NAME_LEN))
			throw EntityParseException::new("nome deve possuir de %d a %d caracteres (nome: $name)", self::MIN_NAME_LEN, self::MAX_NAME_LEN);

		$this->name = $name;
	}

	/**
	 * @return int aquisição da quantidade do produto oferecido pelo preço aceito.
	 */
	public function getAmount(): int
	{
		return $this->amount;
	}

	/**
	 * @param int $amount quantidade do produto oferecido pelo preço aceito.
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
		$this->updateSubPrice();
	}

	/**
	 * @return float aquisição do preço unitário do produto.
	 */
	public function getUnitPrice(): float
	{
		return $this->price / $this->amount;
	}

	/**
	 * @return int aquisição do preço total para aquisição da quantidade do produto.
	 */
	public function getSubprice(): float
	{
		return $this->subprice;
	}

	/**
	 * @param float $subprice preço total para aquisição da quantidade do produto.
	 */
	public function setSubprice(float $subprice): void
	{
		if (!FloatUtil::inInterval($subprice, self::MIN_PRICE, self::MAX_PRICE))
			throw EntityParseException::new("subpreço deve ser de %.2f a %.2f (subpreço: $subprice)", self::MIN_PRICE, self::MAX_PRICE);

		$this->subprice = floatval(sprintf('%.2f', $subprice));
	}

	/**
	 *
	 */
	public function updateSubprice(): void
	{
		$this->setSubprice($this->amountRequest * $this->price);
	}

	/**
	 * @return \DateTime aquisição do horário da última atualização do preço de produto cotado.
	 */
	public function getLastUpdate(): \DateTime
	{
		return $this->lastUpdate;
	}

	/**
	 * @param \DateTime $datetime horário da última atualização do preço de produto cotado.
	 */
	public function setLastUpdate(\DateTime $lastUpdate): void
	{
		$this->lastUpdate = $lastUpdate;
	}

	/**
	 * @return string|NULL aquisição das observações referentes ao preço de produto aceito.
	 */
	public function getObservations(): ?string
	{
		return $this->observations;
	}

	/**
	 * @param string|NULL $observations observações referentes ao preço de produto aceito.
	 */
	public function setObservations(?string $observations)
	{
		$this->observations = $observations;
	}

	/**
	 * {@inheritDoc}
	 * @see \dProject\Primitive\AdvancedObject::getAttributeTypes()
	 */
	public function getAttributeTypes(): array
	{
		return [
			'id' => ObjectUtil::TYPE_INTEGER,
			'idQuotedProductPrice' => ObjectUtil::TYPE_INTEGER,
			'product' => Product::class,
			'provider' => Provider::class,
			'manufacturer' => Manufacturer::class,
			'productPackage' => ProductPackage::class,
			'productType' => ProductType::class,
			'name' => ObjectUtil::TYPE_STRING,
			'amount' => ObjectUtil::TYPE_INTEGER,
			'amountRequest' => ObjectUtil::TYPE_INTEGER,
			'price' => ObjectUtil::TYPE_FLOAT,
			'subprice' => ObjectUtil::TYPE_FLOAT,
			'observations' => ObjectUtil::TYPE_STRING,
			'lastUpdate' => ObjectUtil::TYPE_DATE,
		];
	}
}

