<?php

namespace tercom\control;

use tercom\dao\ProviderContactDAO;
use tercom\entities\Provider;
use tercom\entities\ProviderContact;
use tercom\entities\lists\ProviderContacts;
use tercom\exceptions\ProviderContactException;

/**
 * Controle para Contatos de Fornecedor
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar contatos de fornecedor.
 * Para tal existe uma comunicação direta com a DAO de contatos de fornecedores afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see PhoneControl
 * @see ProviderContact
 * @see ProviderContacts
 * @see ProviderContactDAO
 *
 * @author Andrew
 */
class ProviderContactControl extends GenericControl
{
	/**
	 * @var PhoneControl controle para telefones.
	 */
	private $phoneControl;
	/**
	 * @var ProviderContactDAO DAO para contatos de fornecedor.
	 */
	private $providerContactDAO;

	/**
	 * Cria uma nova instância de um controle para contatos de fornecedor.
	 * Inicia a instância do controle de telefones e DAO para contatos de fornecedor.
	 */
	public function __construct()
	{
		$this->phoneControl = new PhoneControl();
		$this->providerContactDAO = new ProviderContactDAO();
	}

	/**
	 * Inicia uma nova transação com o objeto de adicionar um novo contato de fornecedor
	 * à lista de contatos de um fornecedor, caso não seja possível é executado um RollBack:
	 * @param Provider $provider objeto do tipo fornecedor do qual deseja vincular o contato.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à adicionar.
	 * @return bool true se for adicionado ou false caso contrário.
	 */
	public function add(Provider $provider, ProviderContact $providerContact): bool
	{
		$this->providerContactDAO->beginTransaction();
		{
			if (!$this->providerContactDAO->insert($providerContact) ||
				!$this->providerContactDAO->linkContact($provider, $providerContact))
			{
				$this->providerContactDAO->rollback();
				return false;
			}

			$provider->getContacs()->add($providerContact);
		}
		$this->providerContactDAO->commit();

		return true;
	}

	/**
	 * Atualiza os dados de um contato de fornecedor, neste caso os dados de telefone não são considerados.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function set(ProviderContact $providerContact): bool
	{
		return $this->providerContactDAO->update($providerContact);
	}

	/**
	 * Define os telefones de um contato de fornecedor verificando se é necessário atualizar ou adicionar.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à considerar.
	 * @return bool true se um ou mais telefones tiverem sido adicionados e/ou atualizados.
	 */
	public function setPhones(ProviderContact $providerContact): bool
	{
		$phones = $providerContact->getPhones();

		if ($this->phoneControl->keepPhones($phones))
			return $this->providerContactDAO->updatePhones($providerContact);

		return false;
	}

	/**
	 * Inicia uma nova transação para excluir os dados de um contato de fornecedor e seus telefones.
	 * Caso alguma das operações não possa ser concluída conforme esperado é executado um RollBack.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à excluir.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function remove(ProviderContact $providerContact): bool
	{
		$this->providerContactDAO->beginTransaction();
		{
			$phones = $providerContact->getPhones();

			if ($this->phoneControl->removePhones($phones) != $phones->size() || !$this->providerContactDAO->delete($providerContact))
			{
				$this->providerContactDAO->rollback();
				return false;
			}
		}
		$this->providerContactDAO->commit();

		return true;
	}

	/**
	 * Inicia uma nova transação para realizar a exclusão do telefone comercial de um contato de fornecedor.
	 * Ao excluir o telefone o contato do fornecedor será automaticamente atualizado no banco de dados.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à considerar.
	 * @return bool true se o telefone for excluído ou false caso contrário.
	 */
	public function removeCommercial(ProviderContact $providerContact): bool
	{
		$this->providerContactDAO->beginTransaction();
		{
			if ($providerContact->getCommercial()->getId() !== 0 &&
				!$this->phoneControl->removePhone($providerContact->getCommercial()))
			{
				$this->providerContactDAO->rollback();
				return false;
			}

			$providerContact->setCommercial(null);
		}
		$this->providerContactDAO->commit();

		return true;
	}

	/**
	 * Inicia uma nova transação para realizar a exclusão do telefone secundário de um contato de fornecedor.
	 * Ao excluir o telefone o contato do fornecedor será automaticamente atualizado no banco de dados.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à considerar.
	 * @return bool true se o telefone for excluído ou false caso contrário.
	 */
	public function removeOtherphone(ProviderContact $providerContact): bool
	{
		if ($providerContact->getOtherPhone()->getId() !== 0 &&
			$this->phoneControl->removePhone($providerContact->getOtherPhone()))
		{
			$providerContact->setOtherPhone(null);
			return true;
		}

		return false;
	}

	/**
	 * Obtém os dados de um contato de fornecedor em especifico sendo necessário informar:
	 * @param int $idProvider código de identificação do fornecedor do contato.
	 * @param int $idProviderContact código de identificação do contato de fornecedor.
	 * @throws ProviderContactException apenas se o contato de fornecedor não for encontrado.
	 * @return ProviderContact aquisição do objeto do tipo contato de fornecedor obtido.
	 */
	public function get(int $idProvider, int $idProviderContact): ProviderContact
	{
		if (($providerContact = $this->providerContactDAO->select($idProvider, $idProviderContact)) === null)
			throw ProviderContactException::newNotFound();

		return $providerContact;
	}

	/**
	 * Obtém uma lista contendo os contatos de um fornecedor através do seu código de identificação.
	 * @param int $idProvider código de identificação único do fornecedor à considerar.
	 * @return ProviderContacts aquisição da lista de contatos do fornecedor.
	 */
	public function getByProvider(int $idProvider): ProviderContacts
	{
		return $this->providerContactDAO->selectByProvider($idProvider);
	}

	/**
	 * Obtém uma lista de contatos do fornecedor e define essa lista como nova lista de contatos do fornecedor.
	 * @param Provider $provider objeto do tipo fornecedor do qual deseja carregar os contatos de fornecedor.
	 * @return ProviderContacts aquisição da lista de contatos do fornecedor que foi obtida.
	 */
	public function loadByProvider(Provider $provider): ProviderContacts
	{
		$providerContacts = $this->getProvideContacts($provider->getId());
		$provider->setContacts($providerContacts);

		return $providerContacts;
	}
}

