<?php

namespace tercom\control;

use tercom\entities\Customer;
use tercom\entities\CustomerEmployee;
use tercom\entities\OrderQuote;
use tercom\entities\OrderRequest;
use tercom\entities\TercomEmployee;
use tercom\entities\lists\OrderQuotes;
use tercom\dao\OrderQuoteDAO;
use tercom\exceptions\OrderQuoteException;
use tercom\exceptions\OrderRequestException;
use tercom\TercomException;

/**
 * Controle de Cotação de Pedido
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar cotação de pedido.
 * Para tal existe uma comunicação direta com a DAO de cotação de pedido afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see OrderQuoteDAO
 * @see OrderQuotes
 * @see OrderQuote
 * @see OrderRequest
 *
 * @author Andrew
 */
class OrderQuoteControl extends GenericControl
{
	/**
	 * @var OrderQuoteDAO DAO para cotação de pedido.
	 */
	private $orderQuoteDAO;
	/**
	 * @var OrderRequestControl controle para solicitação de pedido de cotação.
	 */
	private $orderRequestControl;

	/**
	 * Cria uma nova instância para controle de solicitação de pedido de cotação.
	 */
	public function __construct()
	{
		$this->orderQuoteDAO = new OrderQuoteDAO();
		$this->orderRequestControl = new OrderRequestControl();
	}

	/**
	 * Abre uma nova cotação de pedido a partir de uma solicitação de pedido de cotação.
	 * @param OrderRequest $orderRequest objeto do tipo solicitação de pedido de cotação à considerar.
	 * @param TercomEmployee $tercomEmployee objeto do tipo funcionário TERCOM responsável.
	 * @throws OrderRequestException não for possível adicionar a cotação de pedido ou
	 * atualizar o estado da solicitação de pedido de cotação.
	 * @return OrderQuote aquisição do objeto do tipo cotação de pedido criado.
	 */
	public function openQuoting(OrderRequest $orderRequest, TercomEmployee $tercomEmployee): OrderQuote
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		if ($orderRequest->getStatus() !== OrderRequest::ORS_QUEUED)
			throw OrderQuoteException::newOrderRequestNotQueued();

		$orderQuote = new OrderQuote();
		$orderQuote->setOrderRequest($orderRequest);
		$orderQuote->setStatus(OrderQuote::OQS_DOING);

		$this->orderQuoteDAO->beginTransaction();
		{
			if (!$this->orderQuoteDAO->insert($orderQuote))
				throw OrderQuoteException::newInserted();

			$orderRequest->setStatus(OrderRequest::ORS_QUOTING);
			$orderRequest->setTercomEmployee($tercomEmployee);

			try {
				$this->orderRequestControl->set($orderRequest);
			} catch (OrderRequestException $e) {
				$this->orderQuoteDAO->rollback();
				throw $e;
			}
		}
		$this->orderQuoteDAO->commit();

		return $orderQuote;
	}

	/**
	 * Encerra o procedimento de cotação de pedido atualizado o estado para <code>OQS_DONE</code>,
	 * também atualiza a solicitação de pedido de cotação para <code>ORS_QUOTED</code>.
	 * @param OrderQuote $orderQuote objeto do tipo cotação de pedido à concluir.
	 * @throws OrderRequestException cotação de pedido já concluído ou falha ao atualizar.
	 * o estado da cotação de pedido ou solicitação de pedido de cotação.
	 */
	public function closeQuoting(OrderQuote $orderQuote): void
	{
		if ($orderQuote->getStatus() !== OrderQuote::OQS_DOING)
			throw OrderQuoteException::newNotDoing();

		$this->orderQuoteDAO->beginTransaction();
		{
			$orderQuote->setStatus(OrderQuote::OQS_DONE);

			if (!$this->orderQuoteDAO->update($orderQuote))
				throw OrderQuoteException::newUpdated();

			$orderRequest = $orderQuote->getOrderRequest();
			$orderRequest->setStatus(OrderRequest::ORS_QUOTED);

			try {
				$this->orderRequestControl->set($orderRequest);
			} catch (OrderRequestException $e) {
				$this->orderQuoteDAO->rollback();
				throw $e;
			}
		}
		$this->orderQuoteDAO->commit();
	}

	/**
	 * Obtém os dados de uma cotação de pedido através do seu código de identificação único.
	 * @param int $idOrderQuote código de identificação único da cotação de pedido à obter.
	 * @throws TercomException solicitado por funcionário de cliente e feita por outro cliente.
	 * @return OrderQuote aquisição do objeto do tipo pedido de cotação obtido.
	 */
	public function get(int $idOrderQuote): OrderQuote
	{
		if (($orderQuote = $this->orderQuoteDAO->select($idOrderQuote)) === null)
			throw OrderQuoteException::newSelected();

		if (!$this->isTercomManagement())
			if ($orderQuote->getOrderRequest()->getCustomerEmployee()->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newCustomerInvliad();

		return $orderQuote;
	}

	/**
	 * Obtém uma lista de cotações de pedido feitas por um determinado cliente.
	 * Considera qualquer cotação de pedido feita os funcionários detes cliente.
	 * @param Customer $customer objeto do tipo cliente à considerar.
	 * @throws TercomException solicitado por funcionário de cliente e informado outro cliente.
	 * @return OrderQuotes aquisição da lista de cotações de pedidos obtida.
	 */
	public function getByCustomer(Customer $customer): OrderQuotes
	{
		if (!$this->isTercomManagement())
			if ($customer->getId() !== $this->getCustomerLoggedId())
				throw TercomException::newPermissionRestrict();

		return $this->orderQuoteDAO->selectByCustomer($customer);
	}

	/**
	 * Obtém uma lista de cotações de pedido feitas por um determinado funcionário de cliente.
	 * @param CustomerEmployee $customerEmployee objeto do tipo funcionário funcionário de cliente.
	 * @throws TercomException solicitada por funcionário de cliente e não ser do mesmo cliente.
	 * @return OrderQuotes aquisição da lista de cotações de pedidos obtida.
	 */
	public function getByCustomerEmployee(CustomerEmployee $customerEmployee): OrderQuotes
	{
		if (!$this->isTercomManagement())
			if ($customerEmployee->getCustomerProfile()->getCustomerId() !== $this->getCustomerLoggedId())
				throw TercomException::newCustomerInvliad();

		return $this->orderQuoteDAO->selectByCustomerEmployee($customerEmployee);
	}

	/**
	 * Obtém uma lista de cotações de pedido feitas por um determinado funcionário TERCOM.
	 * @param CustomerEmployee $customerEmployee objeto do tipo funcionário TERCOM à considerar.
	 * @throws TercomException solicitado por funcionário de cliente.
	 * @return OrderQuotes aquisição da lista de cotações de pedidos obtida.
	 */
	public function getByTercomEmployee(TercomEmployee $tercomEmployee): OrderQuotes
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->orderQuoteDAO->selectByTercomEmployee($tercomEmployee);
	}

	/**
	 * Obtém uma lista com todas as cotações de pedidos existentes no sitema.
	 * @throws TercomException solicitado por funcionário de cliente.
	 * @return OrderQuotes aquisição da lista de cotações de pedidos obtida.
	 */
	public function getAll(): OrderQuotes
	{
		if (!$this->isTercomManagement())
			throw TercomException::newPermissionRestrict();

		return $this->orderQuoteDAO->selectAll();
	}
}

