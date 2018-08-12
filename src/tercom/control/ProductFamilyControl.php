<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use dProject\MySQL\MySQLException;
use dProject\restful\exception\ApiException;
use tercom\dao\ProductFamilyDAO;
use tercom\entities\ProductFamily;
use tercom\entities\lists\ProductCategories;

class ProductFamilyControl extends GenericControl
{
	/**
	 * @var ProductFamilyDAO
	 */
	private $productFamilyDAO;

	public function __construct(MySQL $mysql)
	{
		$this->productFamilyDAO = new ProductFamilyDAO($mysql);
	}

	private function validate(ProductFamily $productFamily, bool $validateID)
	{
		if ($validateID) {
			if ($productFamily->getID() === 0)
				throw new ControlException('família não identificado');
		} else {
			if ($productFamily->getID() !== 0)
				throw new ControlException('família já identificado');
		}

		if (empty($productFamily->getName()))
			throw new ControlException('nome da família não definido');
	}

	public function add(ProductFamily $productFamily): bool
	{
		$this->validate($productFamily, false);

		// Verifico se existe aqui pra não aumentar o AUTO_INCREMENT em caso de EP_DUP_ENTRY.
		if ($this->productFamilyDAO->selectByName($productFamily->getName()) !== null)
			throw new ApiException('nome da família já utilizado');

		try {
			return $this->productFamilyDAO->insert($productFamily);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				// Improvável, mas possível
				case self::ER_DUP_ENTRY:
					throw new ApiException('nome da família já utilizado');
			}
		}
	}

	public function set(ProductFamily $productFamily): bool
	{
		$this->validate($productFamily, true);

		try {
			return $this->productFamilyDAO->update($productFamily);
		} catch (MySQLException $e) {
			switch ($e->getCode())
			{
				// O nome da família pode se tentar alterar
				case self::ER_DUP_ENTRY:
					throw new ApiException('nome da família já utilizado');
			}
		}
	}

	public function remove(ProductFamily $productFamily): bool
	{
		$this->validate($productFamily, true);

		return $this->productFamilyDAO->delete($productFamily);
	}

	public function has(int $idProductFamily): bool
	{
		return $this->get($idProductFamily) !== null;
	}

	public function get(int $idProductFamily): ?ProductFamily
	{
		if ($idProductFamily < 1)
			throw new ControlException('identificação da família inválida');

		return $this->productFamilyDAO->select($idProductFamily);
	}

	public function search(string $name): ProductCategories
	{
		return $this->productFamilyDAO->searchByName($name);
	}
}

