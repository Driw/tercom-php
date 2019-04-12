<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Customer;
use tercom\entities\CustomerEmployee;
use tercom\entities\lists\OrderQuotes;
use tercom\entities\OrderQuote;
use tercom\entities\OrderRequest;
use tercom\entities\TercomEmployee;
use tercom\exceptions\OrderQuoteException;

/**
 * DAO para Cotação de Pedido
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes as cotações de pedido, incluindo todas operações.
 * Estas operações consiste em: inserir, atualizar e selecionar dados, <b>não podem ser excluídas</b>.
 *
 * Cotação de pedido deve ser feita a partir de uma solicitação de pedido para cotação.
 * Possuem um estado que determina a situação do mesmo no sistema e um horário de registro.
 *
 * @see GenericDAO
 * @see Service
 * @see Services
 *
 * @author Andrew
 */
class OrderQuoteDAO extends GenericDAO
{
	/**
	 * @var array nome das colunas da tabela de cotação de pedido.
	 */
	public const ALL_COLUMNS = ['id', 'idOrderRequest', 'status', 'register'];

	/**
	 * Procedimento interno para validação dos dados de um serviço ao inserir e/ou atualizar.
	 * Cotação de pedido deve possuir uma solicitação de pedido de cotação e deve ser válido.
	 * @param OrderQuote $orderQuote objeto do tipo solicitação de pedido de cotação à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws OrderQuoteException caso algum dos dados do serviço não estejam de acordo.
	 */
	public function validade(OrderQuote $orderQuote, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($orderQuote->getId() === 0)
				throw OrderQuoteException::newNotIdentified();
		} else {
			if ($orderQuote->getId() !== 0)
				throw OrderQuoteException::newIdentified();
		}

		// UNIQUE KEY
		if ($this->existQuotation($orderQuote->getOrderRequest(), $orderQuote->getId())) throw OrderQuoteException::newAlreadyQuoted();

		// FOREIGN KEY
		if (!$this->existOrderRequest($orderQuote->getOrderRequest())) throw OrderQuoteException::newOrderRequestNotFound();
	}

	/**
	 * Insere um nova cotação de pedido no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param OrderQuote $orderQuote objeto do tipo cotação de pedido à adicionar.
	 * @return bool true se conseguir inserir ou false caso contrário.
	 */
	public function insert(OrderQuote $orderQuote): bool
	{
		$this->validade($orderQuote, false);
		$orderQuote->getRegister()->setTimestamp(time());

		$sql = "INSERT INTO order_quotes (idOrderRequest, status, register)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderQuote->getOrderRequestId());
		$query->setInteger(2, $orderQuote->getStatus());
		$query->setDateTime(3, $orderQuote->getRegister());

		if (($result = $query->execute())->isSuccessful())
			$orderQuote->setId($result->getInsertID());

		return $orderQuote->getId();
	}

	/**
	 * Atualiza os dados de uma cotação de pedido já existente no banco de dados.
	 * @param OrderQuote $orderQuote objeto do tipo cotação de pedido à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(OrderQuote $orderQuote): bool
	{
		$this->validade($orderQuote, true);

		$sql = "UPDATE order_quotes
				SET status = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderQuote->getStatus());
		$query->setInteger(2, $orderQuote->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		$orderQuoteColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_quotes');
		$orderRequestColumns = $this->buildQuery(OrderRequestDAO::ALL_COLUMNS, 'order_requests', 'orderRequest');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_COLUMNS, 'customer_employees', 'orderRequest_customerEmployee');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_COLUMNS, 'tercom_employees', 'orderRequest_tercomEmployee');

		return "SELECT $orderQuoteColumns, $orderRequestColumns, $customerEmployeeColumns, $tercomEmployeeColumns
				FROM order_quotes
				INNER JOIN order_requests ON order_requests.id = order_quotes.idOrderRequest
				INNER JOIN customer_employees ON customer_employees.id = order_requests.idCustomerEmployee
				INNER JOIN tercom_employees ON tercom_employees.id = order_requests.idTercomEmployee";
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelectFull(): string
	{
		$orderQuoteColumns = $this->buildQuery(self::ALL_COLUMNS, 'order_quotes');
		$orderRequestColumns = $this->buildQuery(OrderRequestDAO::ALL_COLUMNS, 'order_requests', 'orderRequest');
		$customerEmployeeColumns = $this->buildQuery(CustomerEmployeeDAO::ALL_COLUMNS, 'customer_employees', 'orderRequest_customerEmployee');
		$tercomEmployeeColumns = $this->buildQuery(TercomEmployeeDAO::ALL_COLUMNS, 'tercom_employees', 'orderRequest_tercomEmployee');
		$customerProfileColumns = $this->buildQuery(CustomerProfileDAO::ALL_COLUMNS, 'customer_profiles', 'orderRequest_customerEmployee_customerProfile');
		$customerColumns = $this->buildQuery(CustomerDAO::ALL_COLUMNS, 'customers', 'orderRequest_customerEmployee_customerProfile_customer');

		return "SELECT $orderQuoteColumns, $orderRequestColumns, $customerEmployeeColumns, $tercomEmployeeColumns,
					$customerProfileColumns, $customerColumns
				FROM order_quotes
				INNER JOIN order_requests ON order_requests.id = order_quotes.idOrderRequest
				INNER JOIN customer_employees ON customer_employees.id = order_requests.idCustomerEmployee
				INNER JOIN tercom_employees ON tercom_employees.id = order_requests.idTercomEmployee
				INNER JOIN customer_profiles ON customer_profiles.id = customer_employees.idCustomerProfile
				INNER JOIN customers ON customers.id = customer_profiles.idCustomer";
	}

	/**
	 * Selecione os dados de um pedido de cotação através do seu código de identificação único.
	 * @param int $idOrderQuote código de identificação único do pedido de citação.
	 * @return OrderQuote|NULL pedido de cotação com os dados carregados ou NULL se não encontrado.
	 */
	public function select(int $idOrderQuote): ?OrderQuote
	{
		$sqlSelect = $this->newSelectFull();
		$sql = "$sqlSelect
				WHERE order_quotes.id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idOrderQuote);

		$result = $query->execute();

		return $this->parseOrderQuote($result);
	}

	/**
	 * Selecione os dados de um pedido de cotação filtrados pelo cliente do funcionário de cliente.
	 * @param Customer $customer objeto do tipo cliente à ser filtrado.
	 * @return OrderQuotes aquisição da lista das cotações de pedido filtradas.
	 */
	public function selectByCustomer(Customer $customer): OrderQuotes
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				INNER JOIN customer_profiles ON customer_profiles.id = customer_employees.idCustomerProfile
				WHERE customer_profiles.idCustomer = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customer->getId());

		$result = $query->execute();

		return $this->parseOrderQuotes($result);
	}

	/**
	 * Selecione os dados de um pedido de cotação filtradas pelo funcionário de cliente.
	 * @param CustomerEmployee $customerEmployee objeto do tipo funcionário de cliente à ser filtrado.
	 * @return OrderQuotes aquisição da lista das cotações de pedido filtradas.
	 */
	public function selectByCustomerEmployee(CustomerEmployee $customerEmployee): OrderQuotes
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_requests.idCustomerEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $customerEmployee->getId());

		$result = $query->execute();

		return $this->parseOrderQuotes($result);
	}

	/**
	 * Selecione os dados de um pedido de cotação filtradas pelo funcionário TERCOM.
	 * @param TercomEmployee $tercomEmployee objeto do tipo funcionário TERCOM à ser filtrado.
	 * @return OrderQuotes aquisição da lista das cotações de pedido filtradas.
	 */
	public function selectByTercomEmployee(TercomEmployee $tercomEmployee): OrderQuotes
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE order_requests.idTercomEmployee = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $tercomEmployee->getId());

		$result = $query->execute();

		return $this->parseOrderQuotes($result);
	}

	/**
	 * Seleciona os dados de todas as cotações de pedido registrados no banco de dados sem ordenação.
	 * @return OrderQuotes aquisição da lista das cotações de pedidos autalmente registradas.
	 */
	public function selectAll(): OrderQuotes
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseOrderQuotes($result);
	}

	/**
	 * Verifica se uma determinada solicitação de pedido de cotação já possui uma cotação registrada.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à verificar.
	 * @param int $idOrderQuote condigo de identificação da cotação de pedido à ignorar
	 * ou zero se for inserir uma nova cotação de pedido.
	 * @return bool true se já existir ou false caso contrário.
	 */
	private function existQuotation(OrderRequest $orderRequest, int $idOrderQuote = 0): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_quotes
				WHERE idOrderRequest = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());
		$query->setInteger(2, $idOrderQuote);

		return $this->parseQueryExist($query);
	}

	/**
	 * Verifica se uma determinada solicitação de pedido de cotação existe no sistema.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à verificar.
	 * @return bool true se já existir ou false caso contrário.
	 */
	private function existOrderRequest(OrderRequest $orderRequest): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM order_requests
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $orderRequest->getId());

		return $this->parseQueryExist($query);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de cotação de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return OrderQuote|NULL objeto do tipo cotação de pedido com dados carregados ou NULL se não houver resultado.
	 */
	private function parseOrderQuote(Result $result): ?OrderQuote
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newOrderQuote($entry);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar os objetos de cotação de pedido.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return OrderQuotes aquisição da lista de cotações de pedido a partir da consulta.
	 */
	private function parseOrderQuotes(Result $result): OrderQuotes
	{
		$orderQuotes = new OrderQuotes();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$orderQuote = $this->newOrderQuote($entry);
			$orderQuotes->add($orderQuote);
		}

		return $orderQuotes;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo cotação de pedido e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return OrderQuote aquisição de um objeto do tipo cotação de pedido com dados carregados.
	 */
	private function newOrderQuote(array $entry): OrderQuote
	{
		$this->parseEntry($entry, 'orderRequest');
		$this->parseEntry($entry['orderRequest'], 'customerEmployee', 'tercomEmployee');

		if (isset($entry['orderRequest']['customerEmployee']))
		{
			$this->parseEntry($entry['orderRequest']['customerEmployee'], 'customerProfile');

			if (isset($entry['orderRequest']['customerEmployee']['customerProfile']))
				$this->parseEntry($entry['orderRequest']['customerEmployee']['customerProfile'], 'customer');
		}

		$orderQuote = new OrderQuote();
		$orderQuote->fromArray($entry);

		return $orderQuote;
	}
}

