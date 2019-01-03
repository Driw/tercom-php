<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\dao\exceptions\DAOException;
use tercom\entities\Provider;
use tercom\entities\ServicePrice;
use tercom\entities\lists\ServicePrices;

/**
 * DAO para Preço de Serviço
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos preços de serviço, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar e excluir preços de serviço.
 *
 * Preços de serviços além de possuir um serviço referenciado deve ter obrigatoriamente um fornecedor e preço.
 * Um nome (sobrescreve), descrição adicional são opcionais.
 *
 * @see GenericDAO
 * @see ServicePrice
 * @see ServicePrices
 *
 * @author Andrew
 */
class ServicePriceDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de preços de serviço.
	 */
	public const ALL_COLUMNS = ['idService', 'idProvider', 'name', 'additionalDescription', 'price'];

	/**
	 * Procedimento interno para validação dos dados de um preço de serviço ao inserir e/ou atualizar.
	 * Preços de serviço devem possuir um fornecedor e um preço obrigatoriamente, fornecedor deve existir.
	 * @param ServicePrice $servicePrice objeto do tipo preço de serviço à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws DAOException caso algum dos dados do fornecedor não estejam de acordo.
	 */
	private function validate(ServicePrice $servicePrice, bool $validateId): void
	{
		// NOT NULL
		if ($servicePrice->getPrice() === 0) throw new DAOException('preço do serviço não informado');
		if ($servicePrice->getIdProvider() === 0) throw new DAOException('fornecedor não informado');

		// FOREIGN KEY
		if (!$this->existProvider($servicePrice->getProvider())) throw new DAOException('fornecedor desconhecido');
	}

	/**
	 * Insere um novo preço de serviço no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ServicePrice $servicePrice objeto do tipo preço de serviço à inserir.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(ServicePrice $servicePrice): bool
	{
		$this->validate($servicePrice, false);

		$sql = "INSERT INTO service_values (idService, idProvider, name, additionalDescription, price)
				VALUES (?, ?, ?, ?, ?)";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $servicePrice->getIdService());
		$query->setInteger(2, $servicePrice->getIdProvider());
		$query->setString(3, $servicePrice->getName());
		$query->setString(4, $servicePrice->getAdditionalDescription());
		$query->setFloat(5, $servicePrice->getPrice());
		$query->setEmptyAsNull(true);

		$result = $query->execute();

		if ($result->isSuccessful())
			$servicePrice->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um preço de serviço já existente no banco de dados.
	 * @param ServicePrice $servicePrice objeto do tipo preço de serviço à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(ServicePrice $servicePrice): bool
	{
		$this->validate($servicePrice, true);

		$sql = "UPDATE service_values
				SET name = ?, additionalDescription = ?, price = ?
				WHERE idService = ? AND idProvider = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setString(1, $servicePrice->getName());
		$query->setString(2, $servicePrice->getAdditionalDescription());
		$query->setFloat(3, $servicePrice->getPrice());
		$query->setInteger(4, $servicePrice->getIdService());
		$query->setInteger(5, $servicePrice->getIdProvider());
		$query->setEmptyAsNull(true);

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Exclui os dados de um preço de serviço já existente no banco de dados.
	 * @param ServicePrice $servicePrice objeto do tipo preço de serviço à excluir.
	 * @return bool true se for excluído ou false caso contrário.
	 */
	public function delete(ServicePrice $servicePrice): bool
	{
		$this->validate($servicePrice, true);

		$sql = "DELETE FROM service_values
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $servicePrice->getID());

		$result = $query->execute();

		return $result->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, idService, idProvider, name, additionalDescription, price
				FROM service_values";
	}

	/**
	 * Selecione os dados de um preço de serviço através do seu código de identificação único.
	 * @param int $idServicePrice código de identificação único do preço de serviço.
	 * @return ServicePrice|NULL preço de serviço com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idServicePrice): ServicePrice
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idServicePrice);

		$result = $query->execute();

		return $this->parseServicePrice($result);
	}

	/**
	 * Seleciona os dados dos preços de serviço no banco de dados filtrados pelo serviço.
	 * @param int $idService código de identificação do serviço à filtrar.
	 * @return ServicePrices aquisição da lista de preços de serviço filtrados.
	 */
	public function selectByService(int $idService): ServicePrices
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE idService = ?";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idService);

		$result = $query->execute();

		return $this->parseServicePrices($result);
	}

	/**
	 * Seleciona os dados dos preços de serviço no banco de dados filtrados pelo serviço e fornecedor.
	 * @param int $idService código de identificação do serviço à filtrar.
	 * @param int $idProvider código de identificação do fornecedor à filtrar.
	 * @return ServicePrices aquisição da lista de preços de serviço filtrados.
	 */
	public function selectByProvider(int $idService, int $idProvider): ?ServicePrice
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE idService = ? AND idProvider";

		$query = $this->mysql->createQuery($sql);
		$query->setInteger(1, $idService);
		$query->setInteger(2, $idProvider);

		$result = $query->execute();

		return $this->parseServicePrices($result);
	}

	/**
	 * Verifica se um determinado fornecedor existe no sistema.
	 * @param Provider $provider objeto do tipo fornecedor à verificar.
	 * @return bool true se existir ou false caso contrário.
	 */
	private function existProvider(Provider $provider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de preço de serviço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ServicePrice|NULL objeto do tipo preço de serviço com dados carregados ou NULL se não houver resultado.
	 */
	private function parseServicePrice(Result $result): ?ServicePrice
	{
		if (!$result->hasNext())
			return null;

		$array = $result->next();
		$servicePrice = $this->newServicePrice($array);

		return $servicePrice;
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de preço de serviço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ServicePrices aquisição da lista de preços de serviço a partir da consulta.
	 */
	private function parseServicePrices(Result $result): ServicePrices
	{
		$servicePrices = new ServicePrices();

		while ($result->hasNext())
		{
			$array = $result->next();
			$servicePrice = $this->newServicePrice($array);
			$servicePrices->add($servicePrice);
		}

		return $servicePrices;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo preço de serviço e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ServicePrice aquisição de um objeto do tipo preço de serviço com dados carregados.
	 */
	private function newServicePrice(array $entry): ServicePrice
	{
		$idService = intval($entry['idService']);
		$idProvider = intval($entry['idProvider']);

		$servicePrice = new ServicePrice();
		$servicePrice->fromArray($entry);
		$servicePrice->getService()->setId($idService);
		$servicePrice->getProvider()->setID($idProvider);

		return $servicePrice;
	}
}

