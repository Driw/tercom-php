<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use tercom\entities\Provider;
use tercom\entities\ProviderContact;
use tercom\entities\lists\ProviderContacts;
use tercom\exceptions\ProviderContactException;

/**
 * DAO para Contato de Fornecedor
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos contatos de fornecedor, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar, selecionar um ou mais e excluir contatos de fornecedor.
 *
 * Contatos de fornecedores não possuem um valor único além do seu código de identificação gerado pelo sistema.
 * O nome e endereço de e-mail são obrigatórios; cargo e números de telefone são opcionais.
 *
 * @see GenericDAO
 * @see ProviderContact
 * @see Phone
 *
 * @author Andrew
 */
class ProviderContactDAO extends GenericDAO
{
	/**
	 * Procedimento interno para validação dos dados de um contato de fornecedor ao inserir e/ou atualizar.
	 * Contatos de fornecedor não podem possuir nome ou endereço de e-mail não definidos (em branco).
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProviderContactException caso algum dos dados do contato de fornecedor não estejam de acordo.
	 */
	private function validate(ProviderContact $providerContact, bool $validateId)
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($providerContact->getID() === 0)
				throw ProviderContactException::newNotIdentified();
		} else {
			if ($providerContact->getID() !== 0)
				throw ProviderContactException::newIdentified();
		}

		// NOT NULL
		if (empty($providerContact->getName())) throw ProviderContactException::newNameEmpty();
		if (empty($providerContact->getEmail())) throw ProviderContactException::newEmailEmpty();
	}

	/**
	 * Insere um novo contato de fornecedor no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(ProviderContact $providerContact): bool
	{
		$this->validate($providerContact, false);

		$sql = "INSERT INTO provider_contact (name, position, email, commercial, otherphone)
				VALUES (?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $providerContact->getName());
		$query->setString(2, $providerContact->getPosition());
		$query->setString(3, $providerContact->getEmail());
		$query->setInteger(4, $this->parseNullID($providerContact->getCommercialId()));
		$query->setInteger(5, $this->parseNullID($providerContact->getOtherphoneId()));

		$result = $query->execute();

		if ($result->isSuccessful())
			$providerContact->setID($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um contato de fornecedor já existente no banco de dados.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(ProviderContact $providerContact): bool
	{
		$this->validate($providerContact, true);

		$sql = "UPDATE provider_contact
				SET name = ?, position = ?, email = ?, commercial = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $providerContact->getName());
		$query->setString(2, $providerContact->getPosition());
		$query->setString(3, $providerContact->getEmail());
		$query->setInteger(4, $this->parseNullID($providerContact->getCommercialId()));
		$query->setInteger(5, $this->parseNullID($providerContact->getOtherphoneId()));
		$query->setInteger(6, $providerContact->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza a identificação dos telefones de um contato de fornecedor no banco de dados.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor à atualizar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function updatePhones(ProviderContact $providerContact): bool
	{
		$this->validate($providerContact, true);

		$sql = "UPDATE provider_contact
				SET commercial = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $this->parseNullID($providerContact->getCommercialId()));
		$query->setInteger(2, $this->parseNullID($providerContact->getOtherphoneId()));
		$query->setInteger(3, $providerContact->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Exclui um contato de fornecedor do banco de dados.
	 * @param ProviderContact $providerContact objeto do tipo contato de fornecedor.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function delete(ProviderContact $providerContact):bool
	{
		$sql = "DELETE FROM provider_contact WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $providerContact->getId());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Vincula um contato de fornecedor a um fornecedor sendo necessário ambos já identificados.
	 * @param Provider $provider referência do fornecedor a ter o contato vinculado.
	 * @param ProviderContact $providerContact referência do contato à ser vinculado.
	 * @param int $priority nível de prioridade de exibição na lista de contatos,
	 * quanto maior o nível de prioridade mais a frente na lista vai estar.
	 * @return int quantidade de registros afetados conforme:
	 * <code>REPLACE_NONE</code>, <code>REPLACE_INSERTED</code> ou <code>REPLACE_UPDATED</code>.
	 */
	public function linkContact(Provider $provider, ProviderContact $providerContact, int $priority = 0): int
	{
		$sql = "REPLACE INTO provider_contacts (idProvider, idProviderContact, priority)
				VALUES (?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getId());
		$query->setInteger(2, $providerContact->getId());
		$query->setInteger(3, $priority);

		return ($query->execute())->getAffectedRows();
	}

	/**
	 * Desvincula todos os contatos de fornecedores de um fornecedor identificado.
	 * @param Provider $provider referência do fornecedor a ter os contatos desvinculados.
	 * @return int aquisição da quantidade de contatos que foram desvinculados.
	 */
	public function unlinkContacts(Provider $provider):int
	{
		$sql = "DELETE FROM provider_contacts
				WHERE idProvider = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $provider->getID());

		$result = $query->execute();

		return $result->getAffectedRows();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, name, position, email, commercial commercial_id, otherphone otherphone_id
				FROM provider_contact
				INNER JOIN provider_contacts ON provider_contacts.idProviderContact = provider_contact.id";
	}

	/**
	 * Obtém os dados de contato de fornecedor de um contato identificado.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @param int $idProviderContact código de identificação único do contato.
	 * @return ProviderContact aquisição do contato de fornecedor selecionado.
	 */
	public function select(int $idProvider, int $idProviderContact):?ProviderContact
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE provider_contacts.idProvider = ? AND provider_contacts.idProviderContact = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProvider);
		$query->setInteger(2, $idProviderContact);

		$result = $query->execute();

		return $this->parseProviderContact($result);
	}

	/**
	 * Obtém uma lista com todos os contatos de um fornecedor identificado.
	 * @param int $idProviderContact código de identificação único do fornecedor.
	 * @return ProviderContacts aquisição da lista de contatos do fornecedor.
	 */
	public function selectByProvider(int $idProvider):ProviderContacts
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE provider_contacts.idProvider = ?
				ORDER BY name";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProvider);

		$result = $query->execute();

		return $this->parseProviderContacts($result);
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de contato de fornecedor.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProviderContact|NULL objeto do tipo contato de fornecedor com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProviderContact(Result $result): ?ProviderContact
	{
		return ($entry = $this->parseSingleResult($result)) === null ? null : $this->newProviderContact($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os objetos de contato de fornecedor.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return ProviderContacts aquisição da lista de contatos de fornecedor a partir da consulta.
	 */
	private function parseProviderContacts(Result $result): ProviderContacts
	{
		$providerContacts = new ProviderContacts();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$providerContact = $this->newProviderContact($entry);
			$providerContacts->add($providerContact);
		}

		return $providerContacts;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo contato de fornecedor e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return ProviderContact aquisição de um objeto do tipo contato de fornecedor com dados carregados.
	 */
	private function newProviderContact(array $entry): ProviderContact
	{
		$this->parseEntry($entry, 'commercial', 'otherphone');

		$providerContact = new ProviderContact();
		$providerContact->fromArray($entry);

		return $providerContact;
	}
}

