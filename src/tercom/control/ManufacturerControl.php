<?php

namespace tercom\control;

use tercom\dao\ManufacturerDAO;
use tercom\entities\Manufacturer;
use tercom\entities\lists\Manufacturers;
use tercom\exceptions\ManufacturerException;

class ManufacturerControl extends GenericControl
{
	/**
	 * @var ManufacturerDAO DAO para fabricantes.
	 */
	private $manufactureDAO;

	/**
	 * Construtor para inicializar a instância da DAO para fabricantes.
	 */
	public function __construct()
	{
		$this->manufactureDAO = new ManufacturerDAO();
	}

	/**
	 * Adiciona os dados de um novo fabricante no sistema.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à adicionar.
	 * @throws ManufacturerException quando não for possível adicionar.
	 */
	public function add(Manufacturer $manufacturer): void
	{
		if (!$this->manufactureDAO->insert($manufacturer))
			throw ManufacturerException::newNotInserted();
	}

	/**
	 * Atualiza os dados de um fabricante já existente no sistema.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à atualizar.
	 * @throws ManufacturerException quando não for possível atualizar.
	 */
	public function set(Manufacturer $manufacturer): void
	{
		if (!$this->manufactureDAO->update($manufacturer))
			throw ManufacturerException::newNotUpdated();
	}

	/**
	 * Remove os dados de um fabricante já existente no sistema.
	 * @param Manufacturer $manufacturer objeto do tipo fabricante à remover.
	 * @throws ManufacturerException quando não for possível remover.
	 */
	public function remove(Manufacturer $manufacturer): void
	{
		if (!$this->manufactureDAO->dalete($manufacturer))
			throw ManufacturerException::newNotFound();
	}

	/**
	 * Obtém os dados de um fabricante já existente no sistema pelo código de identificação único.
	 * @param int $idManufacturer código de identificação único do fabricante.
	 * @throws ManufacturerException código de identificação inválido.
	 * @return Manufacturer aquisição dos dados obtidos do fabricante.
	 */
	public function get(int $idManufacturer): Manufacturer
	{
		if (($manufacturer = $this->manufactureDAO->select($idManufacturer)) === null)
			throw ManufacturerException::newNotFound();

		return $manufacturer;
	}

	/**
	 * Obtém uma lista com todos os fabricantes existentes no sistema.
	 * @return Manufacturers aquisição da lista de fabricantes registrados.
	 */
	public function getAll(): Manufacturers
	{
		return $this->manufactureDAO->selectAll();
	}

	/**
	 * Procura por fabricantes através do seu nome fantasia parcial ou completo.
	 * @param string $fantasyName nome fantasia que será utilizado como filtro.
	 * @return Manufacturers
	 */
	public function searchByFantasyName(string $fantasyName): Manufacturers
	{
		return $this->manufactureDAO->selectLikeFantasyName($fantasyName);
	}

	/**
	 * Verifica se o sistema possui um código de identificação do fabricante.
	 * @param int $idManufacturer cógido de identificação do fabricante.
	 * @return bool true se possuir ou false caso contrário.
	 */
	public function has(int $idManufacturer): bool
	{
		return $this->manufactureDAO->exist($idManufacturer);
	}

	/**
	 * Verifica se o sistem apossui um nome de fantasia para fabricante.
	 * @param string $fantasyName nome fantasia do fabricante à verificar.
	 * @param int $idManufacturer cógido de identificação do fabricante à desconsiderar
	 * ou zero caso deseja considerar todos os fabricantes.
	 * @return bool true se possuir ou false caso contrário.
	 */
	public function hasFantasyName(string $fantasyName, int $idManufacturer = 0): bool
	{
		return !$this->manufactureDAO->existFantasyName($fantasyName, $idManufacturer);
	}

	/**
	 * @return array aquisição dos tipos de filtros disponíveis para busca.
	 */
	public static function getFilters(): array
	{
		return [
			'fantasyName' => 'Nome Fantasia',
		];
	}
}

