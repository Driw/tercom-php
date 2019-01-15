<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\ServicePrice;
use tercom\entities\QuotedServicePrice;
use tercom\exceptions\QuotedServicePriceException;

/**
 * DAO para Preço de Serviço Cotado
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos preços de serviço cotado, incluindo todas operações.
 * Estas operações consiste em: adicionar, excluir e selecionar dados dos preços de serviço cotados.
 *
 * Os dados de preços de serviços cotados são réplicas dos preços de serviços em algum momento do sistema.
 *
 * @see GenericDAO
 * @see QuotedServicePrice
 * @see QuotedServicePrices
 *
 * @author Andrew
 */
class QuotedServicePriceDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de preços de serviço.
	 */
	public const ALL_COLUMNS = ['id', 'idService', 'idProvider', 'name', 'additionalDescription', 'price', 'lastUpdate'];
	/**
	 * @var array nome das colunas da tabela de preços de serviço.
	 */
	public const CLONE_COLUMNS = ['idService', 'idProvider', 'name', 'additionalDescription', 'price', 'lastUpdate'];

	/**
	 * Procedimento interno para validação dos dados de um preço de serviço cotado ao inserir.
	 * Preços de serviço cotado precisam ter quantidade, preço, serviço, fornecedor e embalagem informadas,
	 * fabricante e tipo de serviço são informações opcionais e todas são validadas se existem.
	 * @param QuotedServicePrice $quotedServicePrice objeto do tipo preço de serviço à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws QuotedServicePriceException caso algum dos dados do preço de serviço não estejam de acordo.
	 */
	private function validate(QuotedServicePrice $quotedServicePrice, bool $validateID)
	{
		// NOT NULL
		if (StringUtil::isEmpty($quotedServicePrice->getName())) throw QuotedServicePriceException::newNameEmpty();
		if ($quotedServicePrice->getPrice() === 0.0) throw QuotedServicePriceException::newPriceEmpty();
		if ($quotedServicePrice->getServiceId() === 0) throw QuotedServicePriceException::newServiceNone();
		if ($quotedServicePrice->getProviderId() === 0) throw QuotedServicePriceException::newProviderNone();

		// FOREIGN KEY
		if (!$this->existService($quotedServicePrice->getServiceId())) throw QuotedServicePriceException::newServiceInvalid();
		if (!$this->existProvider($quotedServicePrice->getProviderId())) throw QuotedServicePriceException::newProviderInvalid();
	}

	/**
	 * Insere um novo preço de serviço no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ServicePrice $servicePrice objeto do tipo preço de serviço à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function clone(ServicePrice $servicePrice): ?QuotedServicePrice
	{
		$sqlColumnas = implode(', ', self::CLONE_COLUMNS);
		$sql = "INSERT INTO quoted_service_prices ($sqlColumnas)
				SELECT $sqlColumnas FROM service_prices
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $servicePrice->getId());
		$quotedServicePrice = null;

		if (($result = $query->execute())->isSuccessful())
		{
			$quotedServicePrice = new QuotedServicePrice();
			$quotedServicePrice->fromArray($servicePrice->toArray(true));
			$quotedServicePrice->setId($result->getInsertID());
		}

		return $quotedServicePrice;
	}

	/**
	 * Exclui um PREÇO de serviço do banco de dados e considera utilizações.
	 * @param QuotedServicePrice $quotedServicePrice objeto do tipo preço de serviço à excluir.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function delete(QuotedServicePrice $quotedServicePrice): bool
	{
		$sql = "DELETE FROM quoted_service_prices
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $quotedServicePrice->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para se obter os dados completos para se selecionar dados de um preço de pruto.
	 * Envolve todos dados referentes ao serviço do preço e seus detalhes (unidade e categoria).
	 * @return string aquisição da query completa para seleção de dados para preço de serviços.
	 */
	private function newBasicSelect(): string
	{
		$quotedServicePriceColumns = $this->buildQuery(self::ALL_COLUMNS, 'quoted_service_prices');
		$serviceColumns = $this->buildQuery(ServiceDAO::ALL_COLUMNS, 'services', 'service');
		$serviceProviderColumns = $this->buildQuery(ProviderDAO::ALL_COLUMNS, 'providers', 'provider');

		return "SELECT $quotedServicePriceColumns, $serviceColumns, $serviceProviderColumns
				FROM service_prices
				INNER JOIN services ON service_prices.idService = services.id
				INNER JOIN providers ON service_prices.idProvider = providers.id";
	}

	/**
	 * Selecione os dados de um preço de serviço através do seu código de identificação único.
	 * @param int $idQuotedServicePrice código de identificação único do preço de serviço.
	 * @return QuotedServicePrice|NULL preço de serviço com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idQuotedServicePrice): ?QuotedServicePrice
	{
		$sqlSELECT = $this->newFullSelect();
		$sql = "$sqlSELECT
				WHERE service_prices.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idQuotedServicePrice);

		$result = $query->execute();

		return $this->parseQuotedServicePrice($result);
	}

	/**
	 * Verifica se um determinado código de identificação de preço de serviço existe.
	 * @param int $idService código de identificação único do preço de serviço.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existService(int $idService): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idService);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se um determinado código de identificação de fornecedor existe.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existProvider(int $idProvider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProvider);

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto do tipo preço de serviço.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return QuotedServicePrice|NULL objeto instânciado com dados carregados ou NULL se não houver resultado.
	 */
	private function parseQuotedServicePrice(Result $result): ?QuotedServicePrice
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newQuotedServicePrice($entry);
	}

	/**
	 * Procedimento interno para criar um objeto do tipo preço de serviço e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return QuotedServicePrice aquisição de um objeto do tipo preço de serviço com dados carregados.
	 */
	private function newQuotedServicePrice(array $entry): QuotedServicePrice
	{
		$this->parseEntry($entry, 'service', 'provider');

		$quotedServicePrice = new QuotedServicePrice();
		$quotedServicePrice->fromArray($entry);

		return $quotedServicePrice;
	}
}
