<?php

namespace tercom\control;

use tercom\dao\ProviderDAO;
use tercom\entities\Provider;
use tercom\entities\lists\Providers;
use tercom\exceptions\ProviderException;
use tercom\Functions;

/**
 * Controle de Fornecedor
 *
 * Através do controle é definida as especificações de ações permitidas para se gerenciar fornecedores.
 * Para tal existe uma comunicação direta com a DAO de fornecedores afim de persistir no banco de dados.
 * O uso do controle pode ser feito por qualquer outra classe necessária evitando o acesso direto a DAO.
 *
 * @see GenericControl
 * @see ProviderDAO
 * @see Providers
 * @see Provider
 *
 * @author Andrew
 */
class ProviderControl extends GenericControl
{
	/**
	 * @var PhoneControl controle de telfones.
	 */
	private $phoneControl;
	/**
	 * @var ProviderDAO DAO para fornecedores.
	 */
	private $providerDAO;

	/**
	 * Construtor para inicializar as instâncias do controle de telefones e DAO de fornecedores.
	 */
	public function __construct()
	{
		$this->phoneControl = new PhoneControl();
		$this->providerDAO = new ProviderDAO();
	}

	/**
	 * Adiciona um novo fornecedor no sistema.
	 * @param Provider $provider objeto de fornecedor à adicionar.
	 * @return bool true se adicionado ou false caso contrário.
	 */
	public function add(Provider $provider): bool
	{
		return $this->providerDAO->insert($provider);
	}

	/**
	 * Atualiza os dados de um fornecedor já existente no sistema.
	 * @param Provider $provider objeto de fornecedor à atualizar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function set(Provider $provider): bool
	{
		return $this->providerDAO->update($provider);
	}

	/**
	 * Habilita um fornecedor no sistema, ao habilitar estará disponível para clientes.
	 * @param Provider $provider objeto de fornecedor à habilitar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function setEnable(Provider $provider): bool
	{
		$provider->setInactive(false);

		return $this->providerDAO->updateInactive($provider);
	}

	/**
	 * Desabilita um fornecedor no sistema, ao desabilitar estará indisponível para clientes.
	 * @param Provider $provider objeto de fornecedor à desabilitar.
	 * @return bool true se atualizado ou false caso contrário.
	 */
	public function setDisable(Provider $provider): bool
	{
		$provider->setInactive(true);

		return $this->providerDAO->updateInactive($provider);
	}

	/**
	 * Obtém um fornecedor no sistema através do seu código de identificação único.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @throws ProviderException ocorre apenas se o fornecedor não existir.
	 * @return Provider aquisição do objeto de fornecedor desejado.
	 */
	public function get(int $idProvider): Provider
	{
		if (($provider = $this->providerDAO->selectByID($idProvider)) === null)
			throw ProviderException::newNotFound();

		return $provider;
	}

	/**
	 * Obtém um fornecedor no sistema através do seu Cadastro Nacional de Pessoa Jurídica.
	 * @param string $cnpj cadastro nacional de pessoa júridica do fornecedor.
	 * @return Provider aquisição do objeto de fornecedor desejado.
	 */
	public function getByCnpj(string $cnpj): Provider
	{
		if (($provider = $this->providerDAO->selectByCNPJ($cnpj)) === null)
			throw ProviderException::newNotFound();

		return $provider;
	}

	/**
	 * Obtém uma lista com todos os fornecedores registrados no sistema.
	 * @return Providers aquisição da lista de fornecedores encontrados.
	 */
	public function getAll(): Providers
	{
		return $this->providerDAO->selectAll();
	}

	/**
	 * FIXME não deveria existir
	 * @param int $page
	 * @return Providers
	 */
	public function getByPage(int $page): Providers
	{
		return $this->providerDAO->searchByPage($page);
	}

	/**
	 * FIXME não deveria existir
	 * @return int
	 */
	public function getPageCount(): int
	{
		return $this->providerDAO->calcPageCount();
	}

	/**
	 * Realiza uma busca no sistema por fornecedores que possuam um determinado CNPJ.
	 * @param string $cnpj CNPJ parcial ou completo para filtrar os fornecedores.
	 * @return Providers aqusiação da lista de fornecedores coforme o filtro por CNPJ.
	 */
	public function searchByCnpj(string $cnpj): Providers
	{
		return $this->providerDAO->selectLikeCnpj($cnpj);
	}

	/**
	 * Realiza uma busca no sistema por fornecedores que possuam um determinado nome fantasia.
	 * @param string $fantasyName nome fantasia parcial ou completo para filtrar fornecedores.
	 * @return Providers aquisição da lista de fornecedores conforme o filtro por nome fantasia.
	 */
	public function searchByFantasyName(string $fantasyName): Providers
	{
		return $this->providerDAO->selectLikeFantasyName($fantasyName);
	}

	/**
	 * Verifica se um determinado fornecedor existe de fato no sistema.
	 * @param int $idProvider código de identificação único do fornecedor.
	 * @return boolean true se existir ou false caso contrário.
	 */
	public function has(int $idProvider)
	{
		return $this->providerDAO->exist($idProvider);
	}

	/**
	 * Verifica se um determinado cadastro nacional de pessoa jurídica está disponível.
	 * Para estar disponível ou deve pertencer ao mesmo fornecedor ou nenhum.
	 * @param string $cnpj cadastro nacional de pessoa jurídica à verificar.
	 * @param int $idProvider código de identificação do fornecedor (0: novo).
	 * @return bool true se estiver disponível ou false caso contrário.
	 */
	public function hasCnpj(string $cnpj, int $idProvider): bool
	{
		if (!Functions::validateCNPJ($cnpj))
			throw ProviderException::newCnpjUnavaiable();

		return !$this->providerDAO->existCnpj($cnpj, $idProvider);
	}

	/**
	 * Atualiza os dados ou adiciona os dados de telefone de um fornecedor.
	 * @param Provider $provider fornecedor do qual deseja definir os telefones.
	 * @return bool true se definidos ou false caso contrário.
	 */
	public function setPhones(Provider $provider): bool
	{
		if ($this->phoneControl->keepPhones($provider->getPhones()))
			return $this->providerDAO->updatePhones($provider);

		return false;
	}

	/**
	 * Excluí o telefone comercial de um determinado fornecedor.
	 * @param Provider $provider objeto de fornecedor à excluir telefone.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function removeCommercial(Provider $provider): bool
	{
		if ($provider->getCommercial()->getId() !== 0)
			if ($this->phoneControl->removePhone($provider->getCommercial()))
			{
				$provider->setCommercial(null);
				return true;
			}

		return false;
	}

	/**
	 * Excluí o telefone secundário de um determinado fornecedor.
	 * @param Provider $provider objeto de fornecedor à excluir telefone.
	 * @return bool true se excluído ou false caso contrário.
	 */
	public function removeOtherphone(Provider $provider): bool
	{
		if ($provider->getOtherPhone()->getId() !== 0)
			if ($this->phoneControl->removePhone($provider->getOtherPhone()))
			{
				$provider->setOtherPhone(null);
				return true;
			}

		return false;
	}

	/**
	 * @return array aquisição da lista de filtros disponíveis para busca de fornecedores.
	 */
	public static function getFilters(): array
	{
		return [
			'fantasyName' => 'Nome Fantasia',
			'cnpj' => 'CNPJ',
		];
	}
}

?>