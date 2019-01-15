<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\OrderItemService;
use tercom\entities\OrderRequest;
use tercom\entities\QuotedOrderService;
use tercom\entities\lists\QuotedOrderServices;
use tercom\exceptions\QuotedOrderServiceException;

/**
 * DAO para Cotação de Serviço de Pedido
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados as cotações de serviço de pedido, incluindo todas operações.
 * Estas operações consiste em: adicionar, excluir e selecionar, <b>não há necessidade de atualizar</b>.
 *
 * Cotação de serviço de pedido precisa ter apenas um item de serviço de pedido existente.
 *
 * @see GenericDAO
 * @see OrderItemService
 * @see OrderRequest
 * @see QuotedOrderService
 * @see QuotedOrderServices
 *
 * @author Andrew
 */
class QuotedOrderServiceDAO extends GenericDAO
{
	/**
	 * @var array vetor com o nome das colunas da tabela de cotação de serviço de pedido.
	 */
	public const ALL_COLUMNS = ['id', 'idOrderItemService', 'idQuotedServicePrice', 'observations'];

	/**
	 * Procedimento interno para validação dos dados de uma cotação de serviço de pedido ao inserir.
	 * Cotação de serviço de pedido precisa ter apenas um item de serviço de pedido existente.
	 * @param QuotedOrderService $quotedOrderService objeto do tipo cotação de serviço de pedido à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws QuotedOrderServiceException caso algum dos dados da cotação de serviço de pedido não estejam de acordo.
	 */
	public function validate(QuotedOrderService $quotedOrderService, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($quotedOrderService->getId() === 0)
				throw QuotedOrderServiceException::newNotIdentified();
		} else {
			if ($quotedOrderService->getId() !== 0)
				throw QuotedOrderServiceException::newIdentified();
		}

		// FOREIGN KEY
		if (!$this->existOrderItemService($quotedOrderService->getOrderItemService())) throw QuotedOrderServiceException::newItemInvalid();
	}

	/**
	 * Insere uma nova cotação de serviço de pedido no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param QuotedOrderService $quotedOrderService objeto do tipo cotação de serviço de pedido à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(QuotedOrderService $quotedOrderService): bool
	{
		$this->validate($quotedOrderService, false);

		$sql = "INSERT INTO quoted_order_services (idOrderItemService, idQuotedServicePrice, observations)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setInteger(1, $quotedOrderService->getOrderItemServiceId());
		$query->setInteger(2, $quotedOrderService->getQuotedServicePriceId());
		$query->setString(3, $quotedOrderService->getObservations());

		if (($result = $query->execute())->isSuccessful())
			$quotedOrderService->setId($result->getInsertID());

		return $quotedOrderService->getId() !== 0;
	}

	/**
	 * Exclui uma cotação de serviço de pedido do banco de dados.
	 * @param QuotedOrderService $quotedOrderService objeto do tipo cotação de serviço de pedido à excluir.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function delete(QuotedOrderService $quotedOrderService): bool
	{
		$sql = "DELETE FROM quoted_order_services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $quotedOrderService->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui todas as cotações feitas a partir de um item de serviço de pedido para cotação.
	 * @param OrderRequest $orderRequest objeto do tipo pedido de solicitação de cotação do item.
	 * @param OrderItemService $orderItemService objeto do tipo item de serviço de pedido à excluir.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function deleteAll(OrderRequest $orderRequest, OrderItemService $orderItemService): bool
	{
		$sql = "DELETE quoted_order_services
				FROM quoted_order_services
				INNER JOIN order_item_services ON order_item_services.id = quoted_order_services.idOrderItemService
				WHERE quoted_order_services.idOrderItemService = ? AND order_item_services.idOrderRequest = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemService->getId());
		$query->setInteger(2, $orderRequest->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$quotedOrderServiceColumns = $this->buildQuery(self::ALL_COLUMNS, 'quoted_order_services');
		$orderItemServiceColumns = $this->buildQuery(OrderItemServiceDAO::ALL_COLUMNS, 'order_item_services', 'orderItemService');
		$quotedServicePriceColumns = $this->buildQuery(QuotedServicePriceDAO::ALL_COLUMNS, 'quoted_service_prices', 'quotedServicePrice');
		$serviceColumns = $this->buildQuery(ServiceDAO::ALL_COLUMNS, 'services', 'quotedServicePrice_service');

		return "SELECT $quotedOrderServiceColumns, $orderItemServiceColumns, $quotedServicePriceColumns, $serviceColumns
				FROM quoted_order_services
				INNER JOIN order_item_services ON order_item_services.id = quoted_order_services.idOrderItemService
				INNER JOIN quoted_service_prices ON quoted_service_prices.id = quoted_order_services.idQuotedServicePrice
				INNER JOIN services ON services.id = quoted_service_prices.idService";
	}

	/**
	 * Seleciona os dados de uma cotação de serviço de pedido através do código de identificação.
	 * @param int $idQuotedOrderService código de identificação único da cotação de serviço de pedido.
	 * @return QuotedOrderService|NULL aquisição da cotação de serviço de pedido selecionado.
	 */
	public function select(int $idQuotedOrderService): ?QuotedOrderService
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE quoted_order_services.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idQuotedOrderService);

		$result = $query->execute();

		return $this->parseQuotedOrderService($result);
	}

	/**
	 * Seleciona os dados de todas as cotações de serviço de pedido de um item de serviço de pedido.
	 * @param OrderItemService $orderItemService objeto do tipo item de serviço de pedido à considerar.
	 * @return QuotedOrderServices aquisição da lista das cotações de serviço de pedido feita do item.
	 */
	public function selectAll(OrderItemService $orderItemService): QuotedOrderServices
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE quoted_order_services.idOrderItemService = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemService->getId());

		$result = $query->execute();

		return $this->parseQuotedOrderServices($result);
	}

	private function existOrderItemService(OrderItemService $orderItemService): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_item_services
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderItemService->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma determinada cotação de serviço de pedido pertence a uma solicitação de pedido de cotação.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @param QuotedOrderService $quotedOrderService objeto do tipo cotação de serviço de pedido à validar.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function existOnOrderRequest(OrderRequest $orderRequest, QuotedOrderService $quotedOrderService): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM quoted_order_services
				INNER JOIN quoted_service_prices ON quoted_service_prices.id = quoted_order_services.idQuotedServicePrice
				INNER JOIN order_item_services ON order_item_services.id = quoted_order_services.idOrderItemService
				WHERE order_item_services.idOrderRequest = ? AND quoted_order_services.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $quotedOrderService->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de cotação de serviço de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return QuotedOrderService|NULL objeto do tipo cotação de serviço de pedido com dados carregados ou NULL se não houver resultado.
	 */
	private function parseQuotedOrderService(Result $result): ?QuotedOrderService
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newQuotedOrderService($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar as cotações de serviço de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return QuotedOrderServices aquisição da lista de cotação de serviço de pedido a partir da consulta.
	 */
	private function parseQuotedOrderServices(Result $result): QuotedOrderServices
	{
		$quotedOrderServices = new QuotedOrderServices();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$quotedOrderService = $this->newQuotedOrderService($entry);
			$quotedOrderServices->add($quotedOrderService);
		}

		return $quotedOrderServices;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo cotação de serviço de pedido e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return QuotedOrderService aquisição de um objeto do tipo cotação de serviço de pedido com dados carregados.
	 */
	private function newQuotedOrderService(array $entry): QuotedOrderService
	{
		$this->parseEntry($entry, 'quotedServicePrice', 'orderItemService', 'servicePrice');
		$this->parseEntry($entry['quotedServicePrice'], 'service');

		$quotedOrderService = new QuotedOrderService();
		$quotedOrderService->fromArray($entry);

		return $quotedOrderService;
	}
}

