<?php

namespace tercom\dao;

use dProject\MySQL\Result;
use dProject\Primitive\StringUtil;
use tercom\entities\Provider;
use tercom\entities\lists\Providers;
use tercom\exceptions\ProviderException;

/**
 * DAO para Fornecedor
 *
 * Classe responsável pela comunicação completa do sistema para com o banco de dados.
 * Sua responsabilidade é gerenciar os dados referentes aos fornecedores, incluindo todas operações.
 * Estas operações consiste em: adicionar, atualizar e buscar; <b>fornecedores não podem ser excluídos</b>.
 *
 * Fornecedores não podem repetir o CNPJ, portanto cada fornecedor precisa ter um CNPJ único e válido.
 * Razão social e nome fantasia além do CNPJ são os únicos campos obrigatórios, todos os outros são opcionais.
 * Cada fornecedor pode ter até dois telefones que tem seus tipos pré-definidos como comercial e secundário.
 *
 * @see GenericDAO
 * @see Provider
 * @see Phone
 *
 * @author Andrew
 */
class ProviderDAO extends GenericDAO
{
	/**
	 * @var int quantidade de fornecedores por paginação
	 */
	private const PAGE_LENGTH = 10;

	/**
	 * Procedimento interno para validação dos dados de um fornecedor ao inserir e/ou atualizar.
	 * Fornecedores não podem possuir CNPJ, Razão Social e Nome Fantasia não definidos (em branco).
	 * @param Provider $provider objeto do tipo fornecedor à ser validado.
	 * @param bool $validateId true para validar o código de identificação único ou false caso contrário.
	 * @throws ProviderException caso algum dos dados do fornecedor não estejam de acordo.
	 */
	private function validate(Provider $provider, bool $validateId): void
	{
		// PRIMARY KEY
		if ($validateId) {
			if ($provider->getId() === 0)
				throw ProviderException::newNotIdentified();
		} else {
			if ($provider->getId() !== 0)
				throw ProviderException::newIdentified();
		}

		// NOT NULL
		if (StringUtil::isEmpty($provider->getCnpj())) throw ProviderException::newCnpjEmpty();
		if (StringUtil::isEmpty($provider->getCompanyName())) throw ProviderException::newCompanyNameEmpty();
		if (StringUtil::isEmpty($provider->getFantasyName())) throw ProviderException::newFantasyNameEmpty();

		// UNIQUE KEYS
		if ($this->existCnpj($provider->getCnpj(), $provider->getId())) throw ProviderException::newCnpjUnavaiable();
	}

	/**
	 * Insere um novo fornecedor no banco de dados e atualiza o mesmo com o identificador gerado.
	 * @param Provider $provider objeto do tipo fornecedor à adicionar.
	 * @return bool true se conseguir adicionar ou false caso contrário.
	 */
	public function insert(Provider $provider): bool
	{
		$this->validate($provider, false);

		$sql = "INSERT INTO providers (cnpj, companyName, fantasyName, spokesman, site, commercial, otherphone, inactive)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $provider->getCnpj());
		$query->setString(2, $provider->getCompanyName());
		$query->setString(3, $provider->getFantasyName());
		$query->setString(4, $provider->getSpokesman());
		$query->setString(5, $provider->getSite());
		$query->setInteger(6, $this->parseNullID($provider->getCommercialId()));
		$query->setInteger(7, $this->parseNullID($provider->getOtherphoneId()));
		$query->setBoolean(8, $provider->isInactive());

		if (($result = $query->execute())->isSuccessful())
			$provider->setId($result->getInsertID());

		return $result->isSuccessful();
	}

	/**
	 * Atualiza os dados de um fornecedor já existente no banco de dados.
	 * @param Provider $provider objeto do tipo fornecedor à atualizar.
	 * @return bool true se for atualizado ou false caso contrário.
	 */
	public function update(Provider $provider): bool
	{
		$sql = "UPDATE providers
				SET cnpj = ?, companyName = ?, fantasyName = ?, spokesman = ?, site = ?, commercial = ?, otherphone = ?, inactive = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setEmptyAsNull(true);
		$query->setString(1, $provider->getCNPJ());
		$query->setString(2, $provider->getCompanyName());
		$query->setString(3, $provider->getFantasyName());
		$query->setString(4, $provider->getSpokesman());
		$query->setString(5, $provider->getSite());
		$query->setInteger(6, $this->parseNullID($provider->getCommercialId()));
		$query->setInteger(7, $this->parseNullID($provider->getOtherphoneId()));
		$query->setString(8, $provider->isInactive());
		$query->setInteger(9, $provider->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza a identificação dos telefones de um fornecedor no banco de dados.
	 * @param Provider $provider objeto do tipo fornecedor à atualizar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function updatePhones(Provider $provider): bool
	{
		$sql = "UPDATE providers
				SET commercial = ?, otherphone = ?
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $this->parseNullID($provider->getCommercialId()));
		$query->setInteger(2, $this->parseNullID($provider->getOtherphoneId()));
		$query->setInteger(3, $provider->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Atualiza o estado de inatividade de um fornecedor no banco de dados.
	 * @param Provider $provider fornecedor do qual deseja atualizar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function updateInactive(Provider $provider): bool
	{
		$sql = "UPDATE provider SET inactive = ? WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setBoolean(1, $provider->isInactive());
		$query->setInteger(2, $provider->getID());

		return ($query->execute())->isSuccessful();
	}

	/**
	 * Procedimento interno para centralizar e agilizar a manutenção de queries.
	 * @return string aquisição da string de consulta simples para SELECT.
	 */
	private function newSelect(): string
	{
		return "SELECT id, cnpj, companyName, fantasyName, spokesman, site, inactive, commercial commercial_id, otherphone otherphone_id
				FROM providers";
	}

	/**
	 * Selecione os dados de um fornecedor através do seu código de identificação único.
	 * @param int $providerID código de identificação único do fornecedor.
	 * @return Provider|NULL fornecedor com os dados carregados ou NULL se não encontrado.
	 */
	public function selectByID(int $providerID): ?Provider
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $providerID);

		$result = $query->execute();

		return $this->parseProvider($result);
	}

	/**
	 * Seleciona os dados de um fornecedor através do seu Cadastro Nacional de Pessoa Jurídica (CNPJ).
	 * @param string $cnpj número do Cadastro Nacional de Pessoa Jurídica do fornecedor.
	 * @return Provider|NULL fornecedor com os dados carregados ou NULL se não encontrado.
	 */
	public function selectByCnpj(string $cnpj): ?Provider
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE cnpj = ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cnpj);

		$result = $query->execute();

		return $this->parseProvider($result);
	}

	/**
	 * Seleciona os dados de todos os fornecedores registrados no banco de dados sem ordenação.
	 * @return Providers aquisição da lista de fornecedores atualmente registrados.
	 */
	public function selectAll(): Providers
	{
		$sql = $this->newSelect();
		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProviders($result);
	}

	/**
	 * Seleciona os dados dos fornecedores no banco de dados filtrados pelo CNPJ.
	 * @param string $cnpj número parcial ou completo do CNPJ para filtro.
	 * @return Providers aquisição da lista de fornecedores conforme filtro.
	 */
	public function selectLikeCnpj(string $cnpj): Providers
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE cnpj LIKE ?
				ORDER BY fantasyName";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$cnpj%");

		$result = $query->execute();

		return $this->parseProviders($result);
	}

	/**
	 * Seleciona os dados dos fornecedores no banco de dados filtrados pelo nome fantasia.
	 * @param string $fantasyName nome fantasia parcial ou completo para filtro.
	 * @return Providers aquisição da lista de fornecedores conforme filtro.
	 */
	public function selectLikeFantasyName(string $fantasyName): Providers
	{
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE fantasyName LIKE ?
				ORDER BY fantasyName";

		$query = $this->createQuery($sql);
		$query->setString(1, "%$fantasyName%");

		$result = $query->execute();

		return $this->parseProviders($result);
	}

	/**
	 * FIXME isso não deve existir
	 * @param int $page
	 * @return Providers
	 */
	public function searchByPage(int $page): Providers
	{
		$sqlLimit = $page !== -1 ? $this->parsePage($page, self::PAGE_LENGTH) : '';
		$sqlSelect = $this->newSelect();
		$sql = "$sqlSelect
				WHERE inactive = 0
				ORDER BY id DESC
				$sqlLimit";

		$query = $this->createQuery($sql);
		$result = $query->execute();

		return $this->parseProviders($result, true);
	}

	/**
	 * FIXME isso não deve existir
	 * @return int
	 */
	public function calcPageCount(): int
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE inactive = 0";

		$query = $this->createQuery($sql);
		$result = $query->execute();
		$providers = $result->next();

		return ceil(intval($providers['qtd']) / self::PAGE_LENGTH);
	}

	/**
	 * Verifica se um determinado código de identificação de fornecedor existe.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @return bool true se existir ou false caso contrário.
	 */
	public function exist(int $idProvider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE id = ?";

		$query = $this->createQuery($sql);
		$query->setInteger(1, $idProvider);

		$result = $query->execute();
		$providers = $result->next();

		return intval($providers['qtd']) === 1;
	}

	/**
	 * Verifica se um determinado número de CNPJ está disponível para um fornecedor.
	 * @param string $cnpj número do cadastro nacional de pessoa jurídica.
	 * @param int $idProvider código de identificação do fornecedor
	 * ou zero caso seja um novo fornecedor.
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function existCnpj(string $cnpj, int $idProvider): bool
	{
		$sql = "SELECT COUNT(*) qty
				FROM providers
				WHERE cnpj = ? AND id <> ?";

		$query = $this->createQuery($sql);
		$query->setString(1, $cnpj);
		$query->setInteger(2, $idProvider);

		$result = $query->execute();
		$providers = $result->next();

		return intval($providers['qty']) !== 0;
	}

	/**
	 * Procedimento interno para analisar o resultado de uma consulta e criar um objeto de fornecedor.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Provider|NULL objeto do tipo fornecedor com dados carregados ou NULL se não houver resultado.
	 */
	private function parseProvider(Result $result): ?Provider
	{
		return ($entry = $this->parseSingleResult($result)) == null ? null : $this->newProvider($entry);
	}

	/**
	 * Procedimento inerno para analisar o resultado de uma consulta e criar os objetos de fornecedor.
	 * @param Result $result referência do resultado da consulta obtido.
	 * @return Providers aquisição da lista de fornecedores a partir da consulta.
	 */
	private function parseProviders(Result $result): Providers
	{
		$providers = new Providers();

		foreach ($this->parseMultiplyResults($result) as $entry)
		{
			$provider = $this->newProvider($entry);
			$providers->add($provider);
		}

		return $providers;
	}

	/**
	 * Procedimento interno para criar um objeto do tipo fornecedor e carregar os dados de um registro.
	 * @param array $entry vetor contendo os dados do registro obtido de uma consulta.
	 * @return Provider aquisição de um objeto do tipo fornecedor com dados carregados.
	 */
	private function newProvider(array $entry):Provider
	{
		$this->parseEntry($entry, 'commercial', 'otherphone');

		$provider = new Provider();
		$provider->fromArray($entry);

		return $provider;
	}
}

